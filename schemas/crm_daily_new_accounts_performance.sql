-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)
-- Daily count of new IAM accounts for the last 90 days, split into accounts whose
-- linked crm_accounts row (or the underlying iam_accounts row) carries the "LeadOcean"
-- tag vs. accounts that don't. Shows 0 if no data for a given day.

CREATE OR REPLACE VIEW crm_daily_new_accounts_performance AS
WITH day_series AS (
         SELECT generate_series(CURRENT_DATE - '89 days'::interval, CURRENT_DATE::timestamp without time zone, '1 day'::interval)::date AS day_start
        )
 SELECT ds.day_start,
    to_char(ds.day_start::timestamp with time zone, 'YYYY-MM-DD'::text) AS day_code,
    COALESCE(count(ia.id), 0::bigint) AS count,
    COALESCE(count(ia.id) FILTER (WHERE 'LeadOcean' = ANY (COALESCE(ca.tags, ARRAY[]::text[]) || COALESCE(ia.tags, ARRAY[]::text[]))), 0::bigint) AS count_leadocean,
    COALESCE(count(ia.id) FILTER (WHERE NOT ('LeadOcean' = ANY (COALESCE(ca.tags, ARRAY[]::text[]) || COALESCE(ia.tags, ARRAY[]::text[])))), 0::bigint) AS count_without_leadocean
   FROM day_series ds
     LEFT JOIN iam_accounts ia ON ia.created_at::date = ds.day_start AND ia.deleted_at IS NULL
     LEFT JOIN crm_accounts ca ON ca.iam_account_id = ia.id AND ca.deleted_at IS NULL
  GROUP BY ds.day_start
  ORDER BY ds.day_start DESC;
