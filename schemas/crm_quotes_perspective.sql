-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)

CREATE OR REPLACE VIEW crm_quotes_perspective AS
SELECT cq.id,
    cq.uuid,
    cq.name,
    cq.description,
    cq.total_amount,
    cq.detailed_amount,
    ( SELECT n_ia.name
           FROM iam_accounts n_ia
          WHERE n_ia.id = cq.iam_account_id) AS seller_name,
    ( SELECT n_iu.fullname
           FROM iam_users n_iu
          WHERE n_iu.id = cq.iam_user_id) AS representative_name,
    ( SELECT n_ia.name
           FROM iam_accounts n_ia
          WHERE n_ia.id = (( SELECT n_ca.iam_account_id
                   FROM crm_accounts n_ca
                  WHERE n_ca.id = ca.crm_account_id))) AS buyer_name,
    cq.common_currency_id,
    ( SELECT n_cc.code
           FROM common_currencies n_cc
          WHERE n_cc.id = cq.common_currency_id) AS currency_code,
    cq.suggested_price,
    cq.approval_level,
    cq.tags,
    cq.crm_opportunity_id,
    cq.iam_account_id,
    cq.iam_user_id,
    ( SELECT count(n_cql.id) AS count
           FROM crm_quote_items n_cql
          WHERE n_cql.crm_quote_id = cq.id) AS line_count,
    cq.created_at,
    cq.updated_at,
    cq.deleted_at
   FROM crm_quotes cq
     JOIN crm_opportunities ca ON cq.crm_opportunity_id = ca.id;
