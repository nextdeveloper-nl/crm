-- PostgreSQL

CREATE TABLE crm_account_managers (
    id              bigint NOT NULL DEFAULT nextval('crm_account_managers_id_seq'::regclass),
    uuid            uuid DEFAULT gen_random_uuid(),
    crm_account_id  bigint NOT NULL,
    iam_user_id     bigint NOT NULL,
    iam_account_id  bigint,
    created_at      timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at      timestamp with time zone,
    CONSTRAINT crm_account_managers_pkey PRIMARY KEY (id)
);
