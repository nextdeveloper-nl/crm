-- PostgreSQL

CREATE TABLE crm_sector_focus (
    id    bigint NOT NULL DEFAULT nextval('crm_sector_focus_id_seq'::regclass),
    uuid  uuid DEFAULT gen_random_uuid(),
    name  text NOT NULL,
    CONSTRAINT crm_sector_focus_pkey PRIMARY KEY (id)
);
