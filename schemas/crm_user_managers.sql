-- PostgreSQL

CREATE TABLE crm_user_managers (
    id                   bigint NOT NULL DEFAULT nextval('crm_user_managers_id_seq'::regclass),
    uuid                 uuid DEFAULT gen_random_uuid(),
    crm_user_id          bigint NOT NULL,
    iam_user_id          bigint NOT NULL, -- This is the user who is responsible from this user/contact
    created_at           timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at           timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at           timestamp with time zone,
    iam_account_id       bigint NOT NULL,
    relationship_rating  integer DEFAULT 0,
    notes                text,
    CONSTRAINT crm_user_managers_pkey PRIMARY KEY (id)
);
