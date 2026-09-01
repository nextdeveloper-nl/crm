-- PostgreSQL

CREATE TABLE crm_campaign_targets (
    crm_target_id    bigint NOT NULL,
    crm_campaign_id  bigint NOT NULL,
    created_at       timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at       timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
    id               bigint NOT NULL DEFAULT nextval('crm_campaign_targets_id_seq'::regclass),
    uuid             uuid NOT NULL DEFAULT gen_random_uuid(),
    CONSTRAINT crm_campaign_targets_pkey PRIMARY KEY (id),
    CONSTRAINT crm_campaign_targets_crm_target_id_crm_campaign_id_key UNIQUE (crm_target_id, crm_campaign_id)
);
