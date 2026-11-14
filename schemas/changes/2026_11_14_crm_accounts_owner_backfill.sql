-- PostgreSQL
-- CRM multi-tenancy: ownership backfill for crm_accounts. REVIEW BEFORE RUNNING. Run after
-- 2026_11_14_crm_accounts_owner.sql, on staging first.
--
-- After the schema change every existing crm_accounts row is owned by the platform tenant (5). Customers that
-- another tenant works with must be owned by that tenant, otherwise the tenant no longer sees them once
-- CRM_TENANT_ISOLATION_ENABLED is switched on.
--
-- Evidence of "a tenant works with this customer": crm_opportunities.iam_account_id is the seller (tenant) and
-- crm_accounts.id the customer's CRM record. Check the preview first.

-- 1. PREVIEW (read-only): customers whose opportunities are sold by a tenant other than 5.
--
--   SELECT ca.id AS crm_account_id, ca.iam_account_id AS customer, o.iam_account_id AS seller_tenant, count(*) AS opportunities
--   FROM crm_accounts ca
--   JOIN crm_opportunities o ON o.crm_account_id = ca.id AND o.deleted_at IS NULL
--   WHERE ca.deleted_at IS NULL AND o.iam_account_id <> 5
--   GROUP BY 1, 2, 3 ORDER BY 4 DESC;
--
-- 2. If a customer is worked by more than one tenant, it needs one crm_accounts row PER tenant (the unique key is
--    (iam_account_id, owner_iam_account_id)); the statement below creates the missing per-tenant rows, copying the
--    descriptive columns. Opportunities then have to be re-pointed to the tenant's own row (step 3).

BEGIN;

INSERT INTO crm_accounts (iam_account_id, owner_iam_account_id, is_paying_customer, risk_level, common_city_id,
                          position, tags, additional_information, office_phone_number, office_phone_extension,
                          office_email)
SELECT DISTINCT ca.iam_account_id, o.iam_account_id, ca.is_paying_customer, ca.risk_level, ca.common_city_id,
                ca.position, ca.tags, ca.additional_information, ca.office_phone_number, ca.office_phone_extension,
                ca.office_email
FROM crm_accounts ca
JOIN crm_opportunities o ON o.crm_account_id = ca.id AND o.deleted_at IS NULL
WHERE ca.deleted_at IS NULL
  AND ca.owner_iam_account_id = 5
  AND o.iam_account_id <> 5
ON CONFLICT DO NOTHING;

-- 3. Re-point each tenant's opportunities to that tenant's own CRM record of the customer.
UPDATE crm_opportunities o
SET crm_account_id = tenant_ca.id
FROM crm_accounts platform_ca
JOIN crm_accounts tenant_ca
  ON tenant_ca.iam_account_id = platform_ca.iam_account_id
 AND tenant_ca.deleted_at IS NULL
WHERE o.crm_account_id = platform_ca.id
  AND platform_ca.owner_iam_account_id = 5
  AND o.iam_account_id <> 5
  AND tenant_ca.owner_iam_account_id = o.iam_account_id;

-- NOTE: rows created here are tenant-owned with is_created_by_owner = false (see 2026_11_14_crm_accounts_created_by_owner.sql);
-- the tenant does not see the customer's members until that flag is set for the customers it really created.
--
-- 4. Check, then COMMIT or ROLLBACK:
--   SELECT owner_iam_account_id, count(*) FROM crm_accounts WHERE deleted_at IS NULL GROUP BY 1 ORDER BY 2 DESC;

COMMIT;
