-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)

CREATE OR REPLACE VIEW crm_monthly_new_accounts_per_dist_performance AS
WITH month_series AS (
         SELECT date_trunc('month'::text, generate_series(date_trunc('month'::text, CURRENT_DATE - '11 mons'::interval)::timestamp with time zone, date_trunc('month'::text, CURRENT_DATE::timestamp with time zone), '1 mon'::interval))::date AS month_start
        ), accounts_with_dists AS (
         SELECT date_trunc('month'::text, ia_1.created_at::date::timestamp with time zone) AS month,
            aa.distributor_id,
            count(ia_1.id) AS count
           FROM iam_accounts ia_1
             JOIN accounting_accounts aa ON ia_1.id = aa.iam_account_id
          WHERE ia_1.deleted_at IS NULL
          GROUP BY (date_trunc('month'::text, ia_1.created_at::date::timestamp with time zone)), aa.distributor_id
        )
 SELECT ms.month_start,
    (ms.month_start + '1 mon'::interval - '1 day'::interval)::date AS month_end,
    to_char(ms.month_start::timestamp with time zone, 'Month YYYY'::text) AS month_name,
    to_char(ms.month_start::timestamp with time zone, 'YYYY-MM'::text) AS month_code,
    ia.count,
    ia.distributor_id
   FROM month_series ms
     LEFT JOIN accounts_with_dists ia ON ia.month = ms.month_start
  GROUP BY ms.month_start, ia.count, ia.distributor_id
  ORDER BY ms.month_start DESC;
