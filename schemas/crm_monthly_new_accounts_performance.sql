-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)
-- Monthly count of new IAM accounts for the last 12 months (1 year), showing 0 if no data

CREATE OR REPLACE VIEW crm_monthly_new_accounts_performance AS
WITH month_series AS (
         SELECT date_trunc('month'::text, generate_series(date_trunc('month'::text, CURRENT_DATE - '11 mons'::interval)::timestamp with time zone, date_trunc('month'::text, CURRENT_DATE::timestamp with time zone), '1 mon'::interval))::date AS month_start
        )
 SELECT ms.month_start,
    (ms.month_start + '1 mon'::interval - '1 day'::interval)::date AS month_end,
    to_char(ms.month_start::timestamp with time zone, 'Month YYYY'::text) AS month_name,
    to_char(ms.month_start::timestamp with time zone, 'YYYY-MM'::text) AS month_code,
    COALESCE(count(ia.id), 0::bigint) AS count
   FROM month_series ms
     LEFT JOIN iam_accounts ia ON date_trunc('month'::text, ia.created_at::date::timestamp with time zone) = ms.month_start AND ia.deleted_at IS NULL
  GROUP BY ms.month_start
  ORDER BY ms.month_start DESC;
