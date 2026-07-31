-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)

CREATE OR REPLACE VIEW crm_opportunities_perspective AS
SELECT co.id,
    co.uuid,
    co.name,
    co.description,
    co.probability,
    co.opportunity_stage,
    co.source,
    co.income,
    co.deadline,
    ia.name AS account_name,
    ca.id AS crm_account_id,
    ra.name AS responsible_account,
    ru.fullname AS responsible_name,
    COALESCE(q.quote_count, 0::bigint) AS quote_count,
    COALESCE(m.meeting_count, 0::bigint) AS meeting_count,
    COALESCE(c.call_count, 0::bigint) AS call_count,
    COALESCE(p.project_count, 0::bigint) AS project_count,
    co.type,
    co.iam_user_id,
    co.iam_account_id,
    co.tags,
    co.created_at,
    co.updated_at,
    co.deleted_at
   FROM crm_opportunities co
     JOIN crm_accounts ca ON co.crm_account_id = ca.id
     JOIN iam_accounts ia ON ca.iam_account_id = ia.id
     LEFT JOIN iam_accounts ra ON ra.id = co.iam_account_id
     LEFT JOIN iam_users ru ON ru.id = co.iam_user_id
     LEFT JOIN ( SELECT crm_quotes.crm_opportunity_id,
            count(*) AS quote_count
           FROM crm_quotes
          GROUP BY crm_quotes.crm_opportunity_id) q ON q.crm_opportunity_id = co.id
     LEFT JOIN ( SELECT crm_meetings.crm_opportunity_id,
            count(*) AS meeting_count
           FROM crm_meetings
          GROUP BY crm_meetings.crm_opportunity_id) m ON m.crm_opportunity_id = co.id
     LEFT JOIN ( SELECT crm_calls.crm_opportunity_id,
            count(*) AS call_count
           FROM crm_calls
          GROUP BY crm_calls.crm_opportunity_id) c ON c.crm_opportunity_id = co.id
     LEFT JOIN ( SELECT crm_projects.crm_opportunity_id,
            count(*) AS project_count
           FROM crm_projects
          GROUP BY crm_projects.crm_opportunity_id) p ON p.crm_opportunity_id = co.id
  WHERE ca.deleted_at IS NULL AND co.deleted_at IS NULL AND (co.opportunity_stage <> ALL (ARRAY['won'::opportunity_stage, 'lost'::opportunity_stage]));
