-- PostgreSQL

CREATE TABLE crm_users (
    id                   bigint NOT NULL DEFAULT nextval('crm_users_id_seq'::regclass),
    uuid                 uuid DEFAULT gen_random_uuid(),
    iam_user_id          bigint NOT NULL,
    position             text,
    job                  text,
    job_description      text,
    hobbies              text,
    city                 text,
    relationship_status  text,
    is_evangelist        boolean DEFAULT false,
    is_single            boolean,
    child_count          smallint,
    tags                 text[] NOT NULL DEFAULT '{}'::text[],
    created_at           timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at           timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at           timestamp with time zone,
    risk                 risk,
    education_level      education_level,
    is_suspended         boolean,
    CONSTRAINT crm_users_pkey PRIMARY KEY (id)
);
