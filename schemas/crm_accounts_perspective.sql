-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)

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
    iam_accounts.deleted_at
   FROM iam_accounts
     JOIN crm_accounts ON iam_accounts.id = crm_accounts.iam_account_id
     LEFT JOIN iam_users iu ON iam_accounts.iam_user_id = iu.id
     LEFT JOIN accounting_accounts aa ON iam_accounts.id = aa.iam_account_id
     LEFT JOIN common_domains u_cd ON iam_accounts.common_domain_id = u_cd.id
     LEFT JOIN common_countries u_cc ON iam_accounts.common_country_id = u_cc.id
     LEFT JOIN iam_account_types n_iat ON iam_accounts.iam_account_type_id = n_iat.id
  WHERE iam_accounts.deleted_at IS NULL;
