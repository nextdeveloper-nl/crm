-- PostgreSQL

CREATE TABLE crm_projects (
    id                  bigint NOT NULL DEFAULT nextval('crm_projects_id_seq'::regclass),
    uuid                uuid DEFAULT gen_random_uuid(),
    name                text NOT NULL,
    url                 text NOT NULL,
    project_id          text,
    token               text,
    crm_account_id      bigint NOT NULL,
    iam_user_id         bigint NOT NULL,
    iam_account_id      bigint NOT NULL,
    created_at          timestamp with time zone DEFAULT now(),
    updated_at          timestamp with time zone DEFAULT now(),
    deleted_at          timestamp with time zone,
    crm_opportunity_id  bigint,
    CONSTRAINT crm_projects_pkey PRIMARY KEY (id)
);
