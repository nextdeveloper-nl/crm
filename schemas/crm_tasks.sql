-- PostgreSQL

CREATE TABLE crm_tasks (
    id              bigint NOT NULL DEFAULT nextval('crm_tasks_id_seq'::regclass),
    uuid            uuid DEFAULT gen_random_uuid(),
    description     text NOT NULL,
    iam_user_id     bigint NOT NULL,
    crm_account_id  bigint,
    priority        integer DEFAULT 1,
    is_finished     boolean DEFAULT false,
    is_delayed      boolean DEFAULT false,
    created_at      timestamp with time zone,
    updated_at      timestamp with time zone,
    deleted_at      timestamp with time zone,
    name            text NOT NULL,
    iam_account_id  bigint NOT NULL,
    object_type     text,
    object_id       bigint,
    due_date        timestamp with time zone,
    CONSTRAINT crm_tasks_pkey PRIMARY KEY (id)
);
