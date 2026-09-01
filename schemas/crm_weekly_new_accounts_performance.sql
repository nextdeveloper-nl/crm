-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)
-- Weekly sum of new IAM accounts for the last 52 weeks (1 year), showing 0 if no data

CREATE OR REPLACE VIEW crm_weekly_new_accounts_performance AS
WITH week_series AS (
         SELECT date_trunc('week'::text, generate_series(CURRENT_DATE - '1 year'::interval, CURRENT_DATE::timestamp without time zone, '7 days'::interval))::date AS week_start
        )
 SELECT ws.week_start,
    ws.week_start + '6 days'::interval AS week_end,
    to_char(ws.week_start::timestamp with time zone, 'IYYY-IW'::text) AS week_number,
    COALESCE(count(ia.id), 0::bigint) AS count
   FROM week_series ws
     LEFT JOIN iam_accounts ia ON date_trunc('week'::text, ia.created_at::date::timestamp with time zone) = ws.week_start AND ia.iam_user_id IS NOT NULL AND ia.created_at >= (CURRENT_DATE - '1 year'::interval) AND ia.deleted_at IS NULL
  GROUP BY ws.week_start
  ORDER BY ws.week_start DESC;
