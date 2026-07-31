-- PostgreSQL

CREATE TABLE crm_quote_items (
    id                              integer NOT NULL DEFAULT nextval('crm_quote_lines_id_seq'::regclass),
    uuid                            uuid DEFAULT gen_random_uuid(),
    crm_quote_id                    bigint NOT NULL,
    marketplace_product_id          bigint NOT NULL,
    marketplace_product_catalog_id  bigint NOT NULL,
    quantity                        integer NOT NULL,
    unit_price                      numeric,
    discount                        numeric DEFAULT 0,
    total_price                     numeric,
    iam_user_id                     bigint,
    iam_account_id                  bigint,
    created_at                      timestamp with time zone DEFAULT now(),
    updated_at                      timestamp with time zone DEFAULT now(),
    deleted_at                      timestamp with time zone,
    common_currency_id              bigint,
    CONSTRAINT crm_quote_items_pkey PRIMARY KEY (id)
);
