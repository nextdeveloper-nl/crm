-- PostgreSQL

CREATE TABLE crm_calls (
    id                  bigint NOT NULL DEFAULT nextval('crm_calls_id_seq'::regclass),
    uuid                uuid DEFAULT gen_random_uuid(),
    description         text NOT NULL, -- [ui:markdown]
    iam_user_id         bigint NOT NULL,
    iam_account_id      bigint NOT NULL,
    crm_account_id      bigint NOT NULL,
    disposition         text,
    duration            integer,
    from_number         text,
    to_number           text,
    call_direction      text,
    created_at          timestamp with time zone,
    updated_at          timestamp with time zone,
    deleted_at          timestamp with time zone,
    name                text NOT NULL,
    crm_opportunity_id  bigint,
    CONSTRAINT crm_calls_pkey PRIMARY KEY (id)
);
