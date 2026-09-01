-- PostgreSQL

CREATE TABLE crm_campaigns (
    id                bigint NOT NULL DEFAULT nextval('crm_campaigns_id_seq'::regclass),
    uuid              uuid DEFAULT gen_random_uuid(),
    name              text NOT NULL,
    description       text,
    start_date        timestamp with time zone,
    end_date          timestamp with time zone,
    status            text,
    iam_account_id    bigint,
    iam_user_id       bigint,
    created_at        timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at        timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
    deleted_at        timestamp with time zone,
    flow_pipeline_id  bigint,
    flow_stage_id     bigint,
    campaign_type     text NOT NULL DEFAULT 'marketing'::text,
    CONSTRAINT check_dates CHECK (((end_date IS NULL) OR (start_date <= end_date))),
    CONSTRAINT crm_campaigns_campaign_type_check CHECK ((campaign_type = ANY (ARRAY['sales'::text, 'marketing'::text]))),
    CONSTRAINT crm_campaigns_status_check CHECK ((status = ANY (ARRAY['draft'::text, 'active'::text, 'paused'::text, 'completed'::text, 'cancelled'::text]))),
    CONSTRAINT crm_campaigns_flow_pipeline_id_fkey FOREIGN KEY (flow_pipeline_id) REFERENCES flow_pipelines(id) ON DELETE SET NULL,
    CONSTRAINT crm_campaigns_flow_stage_id_fkey FOREIGN KEY (flow_stage_id) REFERENCES flow_stages(id) ON DELETE SET NULL,
    CONSTRAINT crm_campaigns_pkey PRIMARY KEY (id)
);
