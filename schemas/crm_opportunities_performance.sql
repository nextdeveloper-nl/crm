-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)

CREATE OR REPLACE VIEW crm_opportunities_performance AS
SELECT iam_account_id,
    count(*) FILTER (WHERE opportunity_stage = 'lead' AND deleted_at IS NULL) AS leads_count,
    count(*) FILTER (WHERE opportunity_stage = 'prospect' AND deleted_at IS NULL) AS prospect_count,
    count(*) FILTER (WHERE opportunity_stage = 'qualification' AND deleted_at IS NULL) AS qualification_count,
    count(*) FILTER (WHERE opportunity_stage = 'research' AND deleted_at IS NULL) AS research_count,
    count(*) FILTER (WHERE opportunity_stage = 'need-analysis' AND deleted_at IS NULL) AS need_analysis_count,
    count(*) FILTER (WHERE opportunity_stage = 'approach' AND deleted_at IS NULL) AS approach_count,
    count(*) FILTER (WHERE opportunity_stage = 'value-proposition' AND deleted_at IS NULL) AS value_proposition_count,
    count(*) FILTER (WHERE opportunity_stage = 'identifying-decision-makers' AND deleted_at IS NULL) AS identifying_decision_makers_count,
    count(*) FILTER (WHERE opportunity_stage = 'proposal' AND deleted_at IS NULL) AS proposal_count,
    count(*) FILTER (WHERE opportunity_stage = 'negotiation' AND deleted_at IS NULL) AS negotiation_count,
    count(*) FILTER (WHERE opportunity_stage = 'won' AND updated_at > (now() - '7 days'::interval) AND deleted_at IS NULL) AS won_count,
    count(*) FILTER (WHERE opportunity_stage = 'lost' AND updated_at > (now() - '7 days'::interval) AND deleted_at IS NULL) AS lost_count,
    count(*) FILTER (WHERE opportunity_stage = 'cancelled' AND deleted_at IS NULL) AS cancelled_count,
    count(*) FILTER (WHERE opportunity_stage = 'perception-analysis' AND deleted_at IS NULL) AS perception_analysis_count,
    count(*) FILTER (WHERE opportunity_stage = 'renewal' AND deleted_at IS NULL) AS renewal_count,
    type
   FROM crm_opportunities
  GROUP BY iam_account_id, type;
