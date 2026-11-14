-- PostgreSQL
-- CRM multi-tenancy: crm_accounts gets an owner (tenant) column.
--
-- Until now crm_accounts.iam_account_id was the CUSTOMER and nothing recorded which tenant owns the CRM record;
-- the trigger on iam_accounts created one crm_accounts row for EVERY iam_account. That is why every account showed
-- up in crm_accounts_perspective and why tenants could see each other's customers.
--
--   crm_accounts.owner_iam_account_id  the tenant (iam_accounts.id) that owns this CRM record.
--                                      Rows created by the iam_accounts trigger belong to the platform tenant (5).
--                                      One customer iam_account may have one CRM record per owning tenant.
--
-- The application keeps working before this file is applied: TenantScope is inert until
-- CRM_TENANT_ISOLATION_ENABLED=true (config crm.tenant_isolation.enabled). Apply this file, check the result, then
-- switch the flag on.
--
-- STEP 0 (read-only, run first): there must be no duplicate live rows per customer, otherwise the unique index
-- below fails and the whole transaction rolls back. Expected result: 0 rows.
--
--   SELECT iam_account_id, count(*) FROM crm_accounts WHERE deleted_at IS NULL GROUP BY 1 HAVING count(*) > 1;
--
-- If it returns rows, merge them first (keep the row with the opportunities / managers, soft-delete the rest).

BEGIN;

ALTER TABLE crm_accounts ADD COLUMN IF NOT EXISTS owner_iam_account_id bigint;

-- Backfill: every existing record belongs to the platform tenant (iam_accounts.id = 5).
-- Records that another tenant legitimately owns are re-assigned afterwards by the backfill script
-- (see schemas/changes/2026_11_14_crm_accounts_owner_backfill.sql, review it before running).
UPDATE crm_accounts SET owner_iam_account_id = 5 WHERE owner_iam_account_id IS NULL;

ALTER TABLE crm_accounts ALTER COLUMN owner_iam_account_id SET NOT NULL;
-- Safety net: a row inserted by code that does not know the column (an old deploy, a raw insert) belongs to the
-- platform tenant instead of failing. Application code stamps the real owner explicitly.
ALTER TABLE crm_accounts ALTER COLUMN owner_iam_account_id SET DEFAULT 5;

ALTER TABLE crm_accounts DROP CONSTRAINT IF EXISTS crm_accounts_owner_iam_account_id_fkey;
ALTER TABLE crm_accounts ADD CONSTRAINT crm_accounts_owner_iam_account_id_fkey
    FOREIGN KEY (owner_iam_account_id) REFERENCES iam_accounts(id);

-- One CRM record per (customer, owning tenant). Soft-deleted rows do not count.
CREATE UNIQUE INDEX IF NOT EXISTS crm_accounts_iam_account_owner_uidx
    ON crm_accounts (iam_account_id, owner_iam_account_id)
    WHERE deleted_at IS NULL;

CREATE INDEX IF NOT EXISTS crm_accounts_owner_iam_account_id_idx
    ON crm_accounts (owner_iam_account_id);

-- The iam_accounts trigger keeps creating the platform's record for every account, now with the owner set.
create or replace function create_crm_account()
    returns trigger
    language plpgsql
as
$function$
BEGIN
    insert into crm_accounts (iam_account_id, owner_iam_account_id)
    values (new.id, 5)
    ON CONFLICT DO NOTHING;

    return new;
end;
$function$;

-- crm_accounts_perspective exposes the owner (column appended last: CREATE OR REPLACE VIEW can only add columns at
-- the end). The full view text is in schemas/crm_accounts_perspective.sql.
CREATE OR REPLACE VIEW crm_accounts_perspective AS
SELECT crm_accounts.id,
    crm_accounts.uuid,
    iam_accounts.name,
    iu.fullname AS account_owners_fullname,
    iu.email AS account_owners_email,
    iu.phone_number AS account_owners_phone_number,
    iam_accounts.common_domain_id,
    u_cd.name AS domain_name,
    iam_accounts.common_country_id,
    u_cc.name AS country_name,
    iam_accounts.phone_number,
    iam_accounts.description,
    iam_accounts.iam_account_type_id,
    n_iat.name AS account_type,
    crm_accounts.is_paying_customer,
    crm_accounts.common_city_id,
    crm_accounts."position",
    crm_accounts.risk_level,
    iam_accounts.iam_user_id,
    iam_accounts.id AS iam_account_id,
    '-1'::integer AS total_user_count,
    '-1'::integer AS registered_user_count,
    crm_accounts.is_sdr_qualified,
    crm_accounts.is_sdr_qualification_required,
    crm_accounts.disqualification_reason,
    crm_accounts.office_phone_number,
    crm_accounts.office_phone_extension,
    crm_accounts.office_email,
    crm_accounts.is_disabled,
    crm_accounts.disabling_reason,
    crm_accounts.is_suspended,
    crm_accounts.suspension_reason,
    ARRAY( SELECT DISTINCT unnest(COALESCE(iam_accounts.tags, ARRAY[]::text[]) || COALESCE(crm_accounts.tags, ARRAY[]::text[])) AS unnest) AS tags,
    iam_accounts.created_at,
    iam_accounts.updated_at,
    iam_accounts.deleted_at,
    crm_accounts.owner_iam_account_id
   FROM iam_accounts
     JOIN crm_accounts ON iam_accounts.id = crm_accounts.iam_account_id
     LEFT JOIN iam_users iu ON iam_accounts.iam_user_id = iu.id
     LEFT JOIN accounting_accounts aa ON iam_accounts.id = aa.iam_account_id
     LEFT JOIN common_domains u_cd ON iam_accounts.common_domain_id = u_cd.id
     LEFT JOIN common_countries u_cc ON iam_accounts.common_country_id = u_cc.id
     LEFT JOIN iam_account_types n_iat ON iam_accounts.iam_account_type_id = n_iat.id
  WHERE iam_accounts.deleted_at IS NULL;

COMMIT;
