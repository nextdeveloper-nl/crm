-- PostgreSQL
-- CRM multi-tenancy: merge duplicate crm_accounts rows. REVIEW BEFORE RUNNING; run BEFORE
-- 2026_11_14_crm_accounts_owner.sql, on staging first.
--
-- Why: the old iam_accounts trigger had `ON CONFLICT DO NOTHING` but crm_accounts had no unique index, so the
-- conflict clause never fired and a customer could get more than one live crm_accounts row (the unique index in
-- the owner migration failed on (iam_account_id=5, owner=5)).
--
-- Rule: per iam_account_id keep the OLDEST live row (lowest id), move everything that points at the duplicates to
-- it, then soft-delete the duplicates (nothing is hard-deleted). Flags/tags/phone of the keeper are filled from a
-- duplicate only where the keeper has no value.

-- 1. PREVIEW (read-only): which customers are duplicated and how much hangs off each row.
--
--   SELECT ca.iam_account_id, ca.id AS crm_account_id, ca.created_at,
--          (SELECT count(*) FROM crm_opportunities x WHERE x.crm_account_id = ca.id AND x.deleted_at IS NULL) AS opportunities,
--          (SELECT count(*) FROM crm_account_managers x WHERE x.crm_account_id = ca.id AND x.deleted_at IS NULL) AS managers,
--          (SELECT count(*) FROM crm_notes x WHERE x.crm_account_id = ca.id AND x.deleted_at IS NULL) AS notes,
--          (SELECT count(*) FROM crm_calls x WHERE x.crm_account_id = ca.id AND x.deleted_at IS NULL) AS calls,
--          (SELECT count(*) FROM crm_meetings x WHERE x.crm_account_id = ca.id AND x.deleted_at IS NULL) AS meetings,
--          (SELECT count(*) FROM crm_tasks x WHERE x.crm_account_id = ca.id AND x.deleted_at IS NULL) AS tasks
--   FROM crm_accounts ca
--   WHERE ca.deleted_at IS NULL
--     AND ca.iam_account_id IN (SELECT iam_account_id FROM crm_accounts WHERE deleted_at IS NULL GROUP BY 1 HAVING count(*) > 1)
--   ORDER BY ca.iam_account_id, ca.id;

BEGIN;

-- keeper per customer: the oldest live row
CREATE TEMP TABLE crm_accounts_dedupe_map ON COMMIT DROP AS
SELECT d.id AS duplicate_id, k.keeper_id
FROM crm_accounts d
JOIN (
    SELECT iam_account_id, min(id) AS keeper_id
    FROM crm_accounts
    WHERE deleted_at IS NULL
    GROUP BY iam_account_id
    HAVING count(*) > 1
) k ON k.iam_account_id = d.iam_account_id
WHERE d.deleted_at IS NULL AND d.id <> k.keeper_id;

-- fill gaps on the keeper from its duplicates
UPDATE crm_accounts k
SET office_phone_number = COALESCE(k.office_phone_number, src.office_phone_number),
    office_email        = COALESCE(k.office_email, src.office_email),
    position            = COALESCE(k.position, src.position),
    additional_information = COALESCE(k.additional_information, src.additional_information),
    is_paying_customer  = COALESCE(k.is_paying_customer, false) OR COALESCE(src.is_paying_customer, false),
    tags                = (SELECT COALESCE(array_agg(DISTINCT t), '{}'::text[]) FROM unnest(k.tags || src.tags) t)
FROM (
    SELECT m.keeper_id,
           max(d.office_phone_number) AS office_phone_number,
           max(d.office_email) AS office_email,
           max(d.position) AS position,
           max(d.additional_information) AS additional_information,
           bool_or(COALESCE(d.is_paying_customer, false)) AS is_paying_customer,
           (SELECT COALESCE(array_agg(DISTINCT t), '{}'::text[]) FROM crm_accounts d2, unnest(d2.tags) t
             WHERE d2.id IN (SELECT duplicate_id FROM crm_accounts_dedupe_map m2 WHERE m2.keeper_id = m.keeper_id)) AS tags
    FROM crm_accounts_dedupe_map m
    JOIN crm_accounts d ON d.id = m.duplicate_id
    GROUP BY m.keeper_id
) src
WHERE k.id = src.keeper_id;

-- move everything that references a duplicate to the keeper
UPDATE crm_opportunities     t SET crm_account_id = m.keeper_id FROM crm_accounts_dedupe_map m WHERE t.crm_account_id = m.duplicate_id;
UPDATE crm_account_managers  t SET crm_account_id = m.keeper_id FROM crm_accounts_dedupe_map m WHERE t.crm_account_id = m.duplicate_id;
UPDATE crm_notes             t SET crm_account_id = m.keeper_id FROM crm_accounts_dedupe_map m WHERE t.crm_account_id = m.duplicate_id;
UPDATE crm_calls             t SET crm_account_id = m.keeper_id FROM crm_accounts_dedupe_map m WHERE t.crm_account_id = m.duplicate_id;
UPDATE crm_meetings          t SET crm_account_id = m.keeper_id FROM crm_accounts_dedupe_map m WHERE t.crm_account_id = m.duplicate_id;
UPDATE crm_tasks             t SET crm_account_id = m.keeper_id FROM crm_accounts_dedupe_map m WHERE t.crm_account_id = m.duplicate_id;
UPDATE crm_projects          t SET crm_account_id = m.keeper_id FROM crm_accounts_dedupe_map m WHERE t.crm_account_id = m.duplicate_id;

-- soft-delete the duplicates
UPDATE crm_accounts SET deleted_at = now()
WHERE id IN (SELECT duplicate_id FROM crm_accounts_dedupe_map);

-- 2. CHECK, then COMMIT (or ROLLBACK). Must return 0 rows:
--   SELECT iam_account_id, count(*) FROM crm_accounts WHERE deleted_at IS NULL GROUP BY 1 HAVING count(*) > 1;

COMMIT;
