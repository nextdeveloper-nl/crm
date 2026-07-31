-- PostgreSQL

CREATE TABLE crm_regulatory_compliance (
    id    bigint NOT NULL DEFAULT nextval('crm_regulatory_compliance_id_seq'::regclass),
    uuid  uuid DEFAULT gen_random_uuid(),
    name  text NOT NULL,
    CONSTRAINT crm_regulatory_compliance_pkey PRIMARY KEY (id)
);
