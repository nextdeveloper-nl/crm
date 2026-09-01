-- PostgreSQL

CREATE TABLE crm_technologies (
    id          bigint NOT NULL DEFAULT nextval('crm_technologies_id_seq'::regclass),
    uuid        uuid DEFAULT gen_random_uuid(),
    name        text,
    created_at  timestamp with time zone DEFAULT now(),
    updated_at  timestamp with time zone DEFAULT now(),
    deleted_at  timestamp with time zone,
    CONSTRAINT crm_technologies_pkey PRIMARY KEY (id)
);
