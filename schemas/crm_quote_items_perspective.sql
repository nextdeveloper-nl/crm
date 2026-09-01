-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)

CREATE OR REPLACE VIEW crm_quote_items_perspective AS
SELECT cqi.id,
    cqi.uuid,
    cqi.marketplace_product_id,
    ( SELECT n_mp.name
           FROM marketplace_products n_mp
          WHERE n_mp.id = cqi.marketplace_product_id) AS product_name,
    cqi.marketplace_product_catalog_id,
    mpc.name AS product_catatalog_name,
    ( SELECT n_cc.code
           FROM common_currencies n_cc
          WHERE n_cc.id = mpc.common_currency_id) AS currency_code,
    cqi.quantity,
    cqi.discount,
    cqi.unit_price,
    cqi.total_price,
    cqi.crm_quote_id,
    cqi.iam_user_id,
    cqi.iam_account_id,
    cqi.created_at,
    cqi.updated_at,
    cqi.deleted_at
   FROM crm_quote_items cqi
     JOIN marketplace_product_catalogs mpc ON cqi.marketplace_product_catalog_id = mpc.id;
