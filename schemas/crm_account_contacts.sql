-- PostgreSQL
-- Links an Agenda contact to a CRM account. The contact itself stays private to its owner (Agenda scope);
-- the link decides who else may see it: private | team | specific (see crm_account_contact_shares).

CREATE TABLE crm_account_contacts (
    id                  bigint NOT NULL DEFAULT nextval('crm_account_contacts_id_seq'::regclass),
    uuid                uuid DEFAULT gen_random_uuid(),
    crm_account_id      bigint NOT NULL,
    agenda_contact_id   bigint NOT NULL,
    role                text,                          -- job title / role of the contact at this CRM account
    is_primary          boolean NOT NULL DEFAULT false,
    visibility          text NOT NULL DEFAULT 'private', -- private | team | specific
    data                json,                          -- free form data about this link
    created_at          timestamp with time zone,
    updated_at          timestamp with time zone,
    deleted_at          timestamp with time zone,
    iam_account_id      bigint NOT NULL,
    iam_user_id         bigint NOT NULL,
    CONSTRAINT crm_account_contacts_pkey PRIMARY KEY (id)
);
