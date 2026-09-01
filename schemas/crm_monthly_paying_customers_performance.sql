-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)
-- Monthly count of unique paying customers (with paid invoices) for the last 12 months (1 year), showing 0 if no data

CREATE OR REPLACE VIEW crm_monthly_paying_customers_performance AS
WITH month_series AS (
         SELECT date_trunc('month'::text, generate_series(date_trunc('month'::text, CURRENT_DATE - '11 mons'::interval)::timestamp with time zone, date_trunc('month'::text, CURRENT_DATE::timestamp with time zone), '1 mon'::interval))::date AS month_start
        ), paying_customers_per_month AS (
         SELECT date_trunc('month'::text, ai.created_at::date::timestamp with time zone) AS month_start,
            count(ai.accounting_account_id) AS count
           FROM accounting_invoices ai
          WHERE ai.is_paid = true AND ai.created_at >= date_trunc('month'::text, CURRENT_DATE - '11 mons'::interval)
          GROUP BY (date_trunc('month'::text, ai.created_at::date::timestamp with time zone))
        )
 SELECT ms.month_start,
    (ms.month_start + '1 mon'::interval - '1 day'::interval)::date AS month_end,
    to_char(ms.month_start::timestamp with time zone, 'Month YYYY'::text) AS month_name,
    to_char(ms.month_start::timestamp with time zone, 'YYYY-MM'::text) AS month_code,
    COALESCE(pc.count, 0::bigint) AS count
   FROM month_series ms
     LEFT JOIN paying_customers_per_month pc ON ms.month_start = pc.month_start
  GROUP BY ms.month_start, pc.count
  ORDER BY ms.month_start DESC;
