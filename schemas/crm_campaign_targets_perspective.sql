-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)

CREATE OR REPLACE VIEW crm_campaign_targets_perspective AS
SELECT ct.id,
    ct.uuid,
    cc.id AS crm_campaign_id,
    cc.name,
    cc.description,
    cc.start_date,
    cc.end_date,
    cc.status,
    ctar.name AS target_name,
    ctar.description AS target_description,
    cc.iam_account_id,
    cc.iam_user_id,
    ( SELECT ia.name
           FROM iam_accounts ia
          WHERE ia.id = cc.iam_account_id) AS responsible_account,
    ( SELECT iu.fullname
           FROM iam_users iu
          WHERE iu.id = cc.iam_user_id) AS responsible_name,
    ( SELECT count(n_ctu.crm_user_id) AS count
           FROM crm_target_users n_ctu
          WHERE n_ctu.crm_target_id = ctar.id) AS target_user_count,
    ct.created_at,
    ct.updated_at
   FROM crm_campaign_targets ct
     JOIN crm_campaigns cc ON ct.crm_campaign_id = cc.id
     JOIN crm_targets ctar ON ct.crm_target_id = ctar.id
  WHERE cc.deleted_at IS NULL AND ctar.deleted_at IS NULL;
