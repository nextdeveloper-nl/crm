-- PostgreSQL
-- CRM multi-tenancy, option 3: remember whether the OWNING tenant created the customer.
--
--   crm_accounts.is_created_by_owner  true  the owner created this customer's iam_account (or it is the platform's own
--                                           record): the owner may see the customer's people.
--                                     false the owner only attached itself to a company that already existed
--                                           (store() reusing a company). The owner gets a CRM record and manager row
--                                           but NOT the company's members, contacts or owner details.
--
-- Apply AFTER 2026_11_14_crm_accounts_owner.sql and BEFORE deploying the code that reads the column.
--
-- Backfill: platform-owned rows (owner 5) are true. Tenant-owned rows default to false (conservative: a tenant does not
-- see members of a customer until it is known to have created it). Review before running, then set true for the
-- customers each tenant really created, e.g. those whose iam_accounts row was created by one of the tenant's users:
--
--   UPDATE crm_accounts ca SET is_created_by_owner = true
--   FROM iam_accounts ia
--   WHERE ia.id = ca.iam_account_id AND ca.owner_iam_account_id <> 5
--     AND ia.iam_user_id IN (SELECT iam_user_id FROM iam_account_user WHERE iam_account_id = ca.owner_iam_account_id);

BEGIN;

ALTER TABLE crm_accounts ADD COLUMN IF NOT EXISTS is_created_by_owner boolean NOT NULL DEFAULT false;

UPDATE crm_accounts SET is_created_by_owner = true WHERE owner_iam_account_id = 5;

-- The iam_accounts trigger creates the platform's own record: always "created by owner".
create or replace function create_crm_account()
    returns trigger
    language plpgsql
as
$function$
BEGIN
    insert into crm_accounts (iam_account_id, owner_iam_account_id, is_created_by_owner)
    values (new.id, 5, true)
    ON CONFLICT DO NOTHING;

    return new;
end;
$function$;

-- crm_accounts_perspective exposes the flag (appended last) so the API can hide the existing account's owner details.
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
    crm_accounts.owner_iam_account_id,
    crm_accounts.is_created_by_owner
   FROM iam_accounts
     JOIN crm_accounts ON iam_accounts.id = crm_accounts.iam_account_id
     LEFT JOIN iam_users iu ON iam_accounts.iam_user_id = iu.id
     LEFT JOIN accounting_accounts aa ON iam_accounts.id = aa.iam_account_id
     LEFT JOIN common_domains u_cd ON iam_accounts.common_domain_id = u_cd.id
     LEFT JOIN common_countries u_cc ON iam_accounts.common_country_id = u_cc.id
     LEFT JOIN iam_account_types n_iat ON iam_accounts.iam_account_type_id = n_iat.id
  WHERE iam_accounts.deleted_at IS NULL;

COMMIT;
