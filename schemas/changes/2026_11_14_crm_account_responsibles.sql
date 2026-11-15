-- PostgreSQL
-- leo4#1237 / panel-v6.2#267: account responsibles.
--
-- crm_account_managers becomes the one source of truth for who is responsible for a CRM account. The module
-- *-success-manager roles (iam_role_user) are derived from it: only rows with role = success_manager hold them.
--
-- crm_account_managers gains:
--   role         success_manager | sales | support | technical. One user may hold several roles on one account.
--   is_primary   at most one live primary per CRM account. Primary is a flag, so any role can be primary.
--   created_by   the user who added the row (actor, not the responsible)
--   updated_by   the user who last changed it
--
-- crm_account_manager_changes: audit trail of every add / remove / role change / primary change / reassign,
-- with the before and after state. Reassign rows share a batch_uuid so a bulk reassign can be undone.
--
-- Backfill (in this order):
--   1. role = success_manager when the user holds a *-success-manager role on the customer's IAM account,
--      otherwise sales
--   2. duplicate live rows of the same (account, user, role) are soft-deleted, keeping the earliest
--   3. success-manager role holders with no row get one (team = a team already managing the account that the
--      user belongs to, else the user's earliest team)
--   4. primary = earliest success_manager row per account, falling back to the earliest row

BEGIN;

ALTER TABLE crm_account_managers ADD COLUMN IF NOT EXISTS role varchar(32) NOT NULL DEFAULT 'sales';
ALTER TABLE crm_account_managers ADD COLUMN IF NOT EXISTS is_primary boolean NOT NULL DEFAULT false;
ALTER TABLE crm_account_managers ADD COLUMN IF NOT EXISTS created_by bigint;
ALTER TABLE crm_account_managers ADD COLUMN IF NOT EXISTS updated_by bigint;

ALTER TABLE crm_account_managers DROP CONSTRAINT IF EXISTS crm_account_managers_role_check;
ALTER TABLE crm_account_managers ADD CONSTRAINT crm_account_managers_role_check
    CHECK (role IN ('success_manager', 'sales', 'support', 'technical'));

ALTER TABLE crm_account_managers DROP CONSTRAINT IF EXISTS crm_account_managers_created_by_fkey;
ALTER TABLE crm_account_managers ADD CONSTRAINT crm_account_managers_created_by_fkey
    FOREIGN KEY (created_by) REFERENCES iam_users(id) ON DELETE SET NULL;
ALTER TABLE crm_account_managers DROP CONSTRAINT IF EXISTS crm_account_managers_updated_by_fkey;
ALTER TABLE crm_account_managers ADD CONSTRAINT crm_account_managers_updated_by_fkey
    FOREIGN KEY (updated_by) REFERENCES iam_users(id) ON DELETE SET NULL;

COMMENT ON COLUMN crm_account_managers.role IS 'success_manager | sales | support | technical';
COMMENT ON COLUMN crm_account_managers.created_by IS '[ro] The user who added this responsible';
COMMENT ON COLUMN crm_account_managers.updated_by IS '[ro] The user who last changed this responsible';

-- 1. role
UPDATE crm_account_managers cam
SET role = 'success_manager'
FROM crm_accounts ca
WHERE ca.id = cam.crm_account_id
  AND cam.deleted_at IS NULL
  AND cam.role <> 'success_manager'
  AND EXISTS (
      SELECT 1
      FROM iam_role_user ru
      JOIN iam_roles r ON r.id = ru.iam_role_id
      WHERE r.name LIKE '%success-manager'
        AND ru.iam_account_id = ca.iam_account_id
        AND ru.iam_user_id = cam.iam_user_id
  );

-- 2. duplicates
UPDATE crm_account_managers cam
SET deleted_at = now()
FROM (
    SELECT id,
           row_number() OVER (PARTITION BY crm_account_id, iam_user_id, role ORDER BY created_at, id) AS rn
    FROM crm_account_managers
    WHERE deleted_at IS NULL
) d
WHERE d.id = cam.id AND d.rn > 1;

-- 3. success managers without a row
INSERT INTO crm_account_managers (crm_account_id, iam_user_id, iam_account_id, role, created_at, updated_at)
SELECT ca.id,
       sm.iam_user_id,
       COALESCE(
           (SELECT cam.iam_account_id
            FROM crm_account_managers cam
            JOIN iam_account_user au ON au.iam_account_id = cam.iam_account_id AND au.iam_user_id = sm.iam_user_id
            WHERE cam.crm_account_id = ca.id AND cam.deleted_at IS NULL
            ORDER BY cam.created_at, cam.id
            LIMIT 1),
           (SELECT au.iam_account_id
            FROM iam_account_user au
            WHERE au.iam_user_id = sm.iam_user_id AND au.iam_account_id <> ca.iam_account_id
            ORDER BY au.id
            LIMIT 1)
       ),
       'success_manager',
       now(),
       now()
FROM (
    SELECT DISTINCT ru.iam_account_id, ru.iam_user_id
    FROM iam_role_user ru
    JOIN iam_roles r ON r.id = ru.iam_role_id
    WHERE r.name LIKE '%success-manager'
) sm
JOIN crm_accounts ca ON ca.iam_account_id = sm.iam_account_id AND ca.deleted_at IS NULL
WHERE NOT EXISTS (
    SELECT 1 FROM crm_account_managers cam
    WHERE cam.crm_account_id = ca.id
      AND cam.iam_user_id = sm.iam_user_id
      AND cam.role = 'success_manager'
      AND cam.deleted_at IS NULL
);

-- 4. primary
UPDATE crm_account_managers SET is_primary = false WHERE is_primary;
UPDATE crm_account_managers cam
SET is_primary = true
FROM (
    SELECT DISTINCT ON (crm_account_id) id
    FROM crm_account_managers
    WHERE deleted_at IS NULL
    ORDER BY crm_account_id, (role = 'success_manager') DESC, created_at, id
) p
WHERE p.id = cam.id;

CREATE UNIQUE INDEX IF NOT EXISTS crm_account_managers_account_user_role_uniq
    ON crm_account_managers (crm_account_id, iam_user_id, role) WHERE deleted_at IS NULL;
CREATE UNIQUE INDEX IF NOT EXISTS crm_account_managers_one_primary_uniq
    ON crm_account_managers (crm_account_id) WHERE is_primary AND deleted_at IS NULL;
CREATE INDEX IF NOT EXISTS crm_account_managers_crm_account_id_idx
    ON crm_account_managers (crm_account_id) WHERE deleted_at IS NULL;

CREATE TABLE IF NOT EXISTS crm_account_manager_changes (
    id                      bigserial PRIMARY KEY,
    uuid                    uuid NOT NULL DEFAULT gen_random_uuid(),
    crm_account_manager_id  bigint REFERENCES crm_account_managers(id) ON DELETE SET NULL,
    crm_account_id          bigint NOT NULL REFERENCES crm_accounts(id) ON DELETE CASCADE,
    responsible_user_id     bigint REFERENCES iam_users(id) ON DELETE SET NULL,
    action                  varchar(32) NOT NULL,
    before                  json,
    after                   json,
    batch_uuid              uuid,
    is_reverted             boolean NOT NULL DEFAULT false,
    iam_user_id             bigint REFERENCES iam_users(id) ON DELETE SET NULL,
    iam_account_id          bigint NOT NULL REFERENCES iam_accounts(id) ON DELETE CASCADE,
    created_at              timestamp with time zone NOT NULL DEFAULT now(),
    updated_at              timestamp with time zone NOT NULL DEFAULT now(),
    deleted_at              timestamp with time zone,
    CONSTRAINT crm_account_manager_changes_action_check
        CHECK (action IN ('added', 'removed', 'role_changed', 'primary_changed', 'reassigned', 'reverted'))
);

COMMENT ON TABLE crm_account_manager_changes IS 'Audit trail of account responsible changes; reassign batches can be undone';
COMMENT ON COLUMN crm_account_manager_changes.responsible_user_id IS '[alias:iam_user_id] The responsible the change is about';
COMMENT ON COLUMN crm_account_manager_changes.iam_user_id IS 'The actor who made the change';

CREATE UNIQUE INDEX IF NOT EXISTS crm_account_manager_changes_uuid_uniq ON crm_account_manager_changes (uuid);
CREATE INDEX IF NOT EXISTS crm_account_manager_changes_account_idx
    ON crm_account_manager_changes (crm_account_id, created_at DESC);
CREATE INDEX IF NOT EXISTS crm_account_manager_changes_batch_idx
    ON crm_account_manager_changes (batch_uuid) WHERE batch_uuid IS NOT NULL;

COMMIT;

-- Rollback:
-- BEGIN;
-- DROP TABLE IF EXISTS crm_account_manager_changes;
-- DROP INDEX IF EXISTS crm_account_managers_account_user_role_uniq;
-- DROP INDEX IF EXISTS crm_account_managers_one_primary_uniq;
-- DROP INDEX IF EXISTS crm_account_managers_crm_account_id_idx;
-- UPDATE crm_account_managers SET deleted_at = NULL WHERE deleted_at >= '<applied at>';  -- undo step 2
-- DELETE FROM crm_account_managers WHERE created_at >= '<applied at>' AND created_by IS NULL;  -- undo step 3
-- ALTER TABLE crm_account_managers DROP COLUMN role, DROP COLUMN is_primary, DROP COLUMN created_by, DROP COLUMN updated_by;
-- COMMIT;
