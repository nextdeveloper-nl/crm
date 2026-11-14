-- PostgreSQL
-- CRM multi-tenancy: crm_account_managers_perspective exposes the managing (tenant) account.
-- iam_account_id of this view is the CUSTOMER account; tenant isolation filters on manager_iam_account_id
-- (crm_account_managers.iam_account_id, the account the manager works for). Column appended last, as
-- CREATE OR REPLACE VIEW only allows adding columns at the end. Apply BEFORE deploying the code that scopes the view.

CREATE OR REPLACE VIEW crm_account_managers_perspective AS
SELECT ca.id,
    ca.uuid,
    ia.id AS iam_account_id,
    ia.name,
    iu.fullname AS account_manager,
    iu.id AS iam_user_id,
    ca.is_paying_customer,
    ca.tags,
    ca.created_at,
    ca.updated_at,
    ca.deleted_at,
    cam.iam_account_id AS manager_iam_account_id
   FROM iam_accounts ia
     JOIN crm_accounts ca ON ia.id = ca.iam_account_id
     JOIN crm_account_managers cam ON ca.id = cam.crm_account_id
     JOIN iam_users iu ON iu.id = cam.iam_user_id;
