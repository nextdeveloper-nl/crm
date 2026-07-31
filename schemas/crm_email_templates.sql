-- PostgreSQL

CREATE TABLE crm_email_templates (
    id                        bigint NOT NULL DEFAULT nextval('crm_email_templates_id_seq'::regclass),
    uuid                      uuid DEFAULT gen_random_uuid(),
    subject                   text NOT NULL,
    content                   text NOT NULL,
    iam_user_id               bigint NOT NULL,
    iam_account_id            bigint NOT NULL,
    email_meta                text,
    created_at                timestamp with time zone,
    updated_at                timestamp with time zone,
    deleted_at                timestamp with time zone,
    crm_campaign_id           bigint,
    communication_channel_id  bigint,
    CONSTRAINT crm_email_templates_pkey PRIMARY KEY (id)
);
