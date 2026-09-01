-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)

CREATE OR REPLACE VIEW crm_account_managers_performance AS
WITH date_series AS (
         SELECT generate_series(CURRENT_DATE - '29 days'::interval, CURRENT_DATE::timestamp without time zone, '1 day'::interval)::date AS creation_date
        ), account_creations AS (
         SELECT date(ca.created_at) AS creation_date,
            iu.fullname AS account_manager,
            cam.iam_account_id,
            count(*) AS total_accounts
           FROM crm_accounts ca
             JOIN crm_account_managers cam ON cam.crm_account_id = ca.id
             JOIN iam_users iu ON iu.id = cam.iam_user_id
          WHERE ca.created_at >= (CURRENT_DATE - '30 days'::interval) AND cam.created_at = (( SELECT min(cam2.created_at) AS min
                   FROM crm_account_managers cam2
                  WHERE cam2.crm_account_id = ca.id))
          GROUP BY (date(ca.created_at)), iu.fullname, cam.iam_account_id
        )
 SELECT ds.creation_date,
    ac.account_manager,
    ac.iam_account_id,
    COALESCE(ac.total_accounts, 0::bigint) AS total_accounts
   FROM date_series ds
     LEFT JOIN account_creations ac ON ac.creation_date = ds.creation_date
  ORDER BY ds.creation_date, ac.account_manager;
