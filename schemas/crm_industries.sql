-- PostgreSQL

CREATE TABLE crm_industries (
    id          bigint NOT NULL DEFAULT nextval('common_industries_id_seq'::regclass),
    uuid        uuid NOT NULL DEFAULT gen_random_uuid(),
    name        text NOT NULL,
    created_at  timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at  timestamp with time zone,
    CONSTRAINT common_industries_pkey PRIMARY KEY (id)
);
