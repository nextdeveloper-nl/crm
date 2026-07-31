-- PostgreSQL

CREATE TABLE crm_notes (
    id              bigint NOT NULL DEFAULT nextval('crm_notes_id_seq'::regclass),
    uuid            uuid DEFAULT gen_random_uuid(),
    crm_account_id  bigint NOT NULL,
    note            text NOT NULL, -- [ui:markdown]
    created_at      timestamp with time zone,
    updated_at      timestamp with time zone,
    deleted_at      timestamp with time zone,
    iam_user_id     bigint,
    iam_account_id  bigint,
    CONSTRAINT crm_notes_pkey PRIMARY KEY (id)
);
