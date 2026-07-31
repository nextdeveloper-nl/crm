-- PostgreSQL

CREATE TABLE crm_opportunities (
    id                  bigint NOT NULL DEFAULT nextval('crm_opportunities_id_seq'::regclass),
    uuid                uuid DEFAULT gen_random_uuid(),
    name                text NOT NULL,
    description         text, -- [ui:markdown]
    probability         smallint DEFAULT '0'::smallint,
    source              text,
    income              numeric DEFAULT 0, -- [ui:money]
    deadline            date,
    iam_account_id      bigint,
    iam_user_id         bigint,
    crm_account_id      bigint NOT NULL,
    tags                text[] DEFAULT '{}'::text[],
    created_at          timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at          timestamp with time zone,
    opportunity_stage   opportunity_stage DEFAULT 'lead'::opportunity_stage,
    type                text DEFAULT 'sales'::text,
    reason_lost         text,
    common_currency_id  bigint,
    crm_campaign_id     bigint,
    CONSTRAINT crm_opportunities_crm_campaign_id_fkey FOREIGN KEY (crm_campaign_id) REFERENCES crm_campaigns(id) ON DELETE SET NULL,
    CONSTRAINT crm_opportunities_pkey PRIMARY KEY (id)
);
