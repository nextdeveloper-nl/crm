-- PostgreSQL

CREATE TABLE crm_offerings (
    id          bigint NOT NULL DEFAULT nextval('crm_offerings_id_seq'::regclass),
    uuid        uuid NOT NULL DEFAULT gen_random_uuid(),
    name        text NOT NULL,
    created_at  timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at  timestamp with time zone,
    CONSTRAINT crm_offerings_pkey PRIMARY KEY (id)
);
