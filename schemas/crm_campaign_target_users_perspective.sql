-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)

CREATE OR REPLACE VIEW crm_campaign_target_users_perspective AS
SELECT DISTINCT ON (cc.id, iu.email) cu.id,
    cu.uuid,
    cc.id AS crm_campaign_id,
    cc.name AS campaign_name,
    cc.status AS campaign_status,
    ctar.id AS crm_target_id,
    ctar.name AS target_name,
    cu.id AS crm_user_id,
    iu.fullname,
    iu.email,
    iu.phone_number,
    ia.id AS iam_account_id,
    ia.name AS account_name,
    cc.iam_account_id AS responsible_account_id,
    ( SELECT r_ia.name
           FROM iam_accounts r_ia
          WHERE r_ia.id = cc.iam_account_id) AS responsible_account,
    cc.created_at,
    cc.updated_at,
    cc.deleted_at
   FROM crm_campaigns cc
     JOIN crm_campaign_targets cct ON cc.id = cct.crm_campaign_id
     JOIN crm_targets ctar ON cct.crm_target_id = ctar.id
     JOIN crm_target_users ctu ON ctar.id = ctu.crm_target_id
     JOIN crm_users cu ON ctu.crm_user_id = cu.id AND cu.deleted_at IS NULL
     JOIN iam_users iu ON cu.iam_user_id = iu.id
     LEFT JOIN iam_account_user iau ON iu.id = iau.iam_user_id AND iau.is_active = true
     LEFT JOIN iam_accounts ia ON iau.iam_account_id = ia.id AND ia.deleted_at IS NULL
  WHERE cc.deleted_at IS NULL AND ctar.deleted_at IS NULL AND iu.deleted_at IS NULL
  ORDER BY cc.id, iu.email, ia.id;
