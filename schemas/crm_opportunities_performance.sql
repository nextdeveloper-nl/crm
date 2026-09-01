-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)

CREATE OR REPLACE VIEW crm_opportunities_performance AS
SELECT iam_account_id,
    count(*) FILTER (WHERE opportunity_stage = 'lead'::opportunity_stage AND deleted_at IS NULL) AS leads_count,
    count(*) FILTER (WHERE opportunity_stage = 'prospect'::opportunity_stage AND deleted_at IS NULL) AS prospect_count,
    count(*) FILTER (WHERE opportunity_stage = 'qualification'::opportunity_stage AND deleted_at IS NULL) AS qualification_count,
    count(*) FILTER (WHERE opportunity_stage = 'research'::opportunity_stage AND deleted_at IS NULL) AS research_count,
    count(*) FILTER (WHERE opportunity_stage = 'need-analysis'::opportunity_stage AND deleted_at IS NULL) AS need_analysis_count,
    count(*) FILTER (WHERE opportunity_stage = 'approach'::opportunity_stage AND deleted_at IS NULL) AS approach_count,
    count(*) FILTER (WHERE opportunity_stage = 'value-proposition'::opportunity_stage AND deleted_at IS NULL) AS value_proposition_count,
    count(*) FILTER (WHERE opportunity_stage = 'identifying-decision-makers'::opportunity_stage AND deleted_at IS NULL) AS identifying_decision_makers_count,
    count(*) FILTER (WHERE opportunity_stage = 'proposal'::opportunity_stage AND deleted_at IS NULL) AS proposal_count,
    count(*) FILTER (WHERE opportunity_stage = 'negotiation'::opportunity_stage AND deleted_at IS NULL) AS negotiation_count,
    count(*) FILTER (WHERE opportunity_stage = 'won'::opportunity_stage AND updated_at > (now() - '7 days'::interval) AND deleted_at IS NULL) AS won_count,
    count(*) FILTER (WHERE opportunity_stage = 'lost'::opportunity_stage AND updated_at > (now() - '7 days'::interval) AND deleted_at IS NULL) AS lost_count,
    count(*) FILTER (WHERE opportunity_stage = 'cancelled'::opportunity_stage AND deleted_at IS NULL) AS cancelled_count,
    count(*) FILTER (WHERE opportunity_stage = 'perception-analysis'::opportunity_stage AND deleted_at IS NULL) AS perception_analysis_count,
    count(*) FILTER (WHERE opportunity_stage = 'renewal'::opportunity_stage AND deleted_at IS NULL) AS renewal_count,
    type
   FROM crm_opportunities
  GROUP BY iam_account_id, type;
