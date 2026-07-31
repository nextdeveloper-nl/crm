-- PostgreSQL

CREATE TABLE crm_targets (
    id               bigint NOT NULL DEFAULT nextval('crm_targets_id_seq'::regclass),
    uuid             uuid DEFAULT gen_random_uuid(),
    name             text NOT NULL,
    description      text,
    iam_account_id   bigint,
    iam_user_id      bigint,
    created_at       timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at       timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
    deleted_at       timestamp with time zone,
    list_user_count  integer,
    type             text DEFAULT 'system'::text,
    CONSTRAINT crm_targets_pkey PRIMARY KEY (id)
);
