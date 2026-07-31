-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)

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
    ca.deleted_at
   FROM iam_accounts ia
     JOIN crm_accounts ca ON ia.id = ca.iam_account_id
     JOIN crm_account_managers cam ON ca.id = cam.crm_account_id
     JOIN iam_users iu ON iu.id = cam.iam_user_id;
