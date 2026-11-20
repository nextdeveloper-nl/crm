-- PostgreSQL
-- Users a crm_account_contacts link (visibility = specific) is shared with.

CREATE TABLE crm_account_contact_shares (
    id                          bigint NOT NULL DEFAULT nextval('crm_account_contact_shares_id_seq'::regclass),
    uuid                        uuid DEFAULT gen_random_uuid(),
    crm_account_contact_id      bigint NOT NULL,
    shared_iam_user_id          bigint NOT NULL,   -- the user the link is shared with
    created_at                  timestamp with time zone,
    updated_at                  timestamp with time zone,
    deleted_at                  timestamp with time zone,
    iam_account_id              bigint NOT NULL,
    iam_user_id                 bigint NOT NULL,   -- the owner who shared it
    CONSTRAINT crm_account_contact_shares_pkey PRIMARY KEY (id)
);
