-- PostgreSQL
-- Contacts of a CRM account: Agenda contacts linked to crm_accounts, with per link role/primary/visibility,
-- and the list of users a link is shared with. Apply by hand (no Laravel migrations in this project).

BEGIN;

CREATE TABLE IF NOT EXISTS crm_account_contacts (
    id                  bigserial PRIMARY KEY,
    uuid                uuid NOT NULL DEFAULT gen_random_uuid(),
    crm_account_id      bigint NOT NULL REFERENCES crm_accounts(id),
    agenda_contact_id   bigint NOT NULL REFERENCES agenda_contacts(id),
    role                text,
    is_primary          boolean NOT NULL DEFAULT false,
    visibility          text NOT NULL DEFAULT 'private',
    data                json,
    created_at          timestamp with time zone,
    updated_at          timestamp with time zone,
    deleted_at          timestamp with time zone,
    iam_account_id      bigint NOT NULL REFERENCES iam_accounts(id),
    iam_user_id         bigint NOT NULL REFERENCES iam_users(id),
    CONSTRAINT crm_account_contacts_visibility_check CHECK (visibility IN ('private', 'team', 'specific'))
);

-- A contact is linked once to an account (its owner makes the link).
CREATE UNIQUE INDEX IF NOT EXISTS crm_account_contacts_unique_link
    ON crm_account_contacts (crm_account_id, agenda_contact_id) WHERE deleted_at IS NULL;
-- One primary contact per (account, owner).
CREATE UNIQUE INDEX IF NOT EXISTS crm_account_contacts_unique_primary
    ON crm_account_contacts (crm_account_id, iam_user_id) WHERE is_primary AND deleted_at IS NULL;
CREATE UNIQUE INDEX IF NOT EXISTS crm_account_contacts_uuid_idx ON crm_account_contacts (uuid);
CREATE INDEX IF NOT EXISTS crm_account_contacts_contact_idx ON crm_account_contacts (agenda_contact_id);
CREATE INDEX IF NOT EXISTS crm_account_contacts_user_idx ON crm_account_contacts (iam_user_id);

CREATE TABLE IF NOT EXISTS crm_account_contact_shares (
    id                          bigserial PRIMARY KEY,
    uuid                        uuid NOT NULL DEFAULT gen_random_uuid(),
    crm_account_contact_id      bigint NOT NULL REFERENCES crm_account_contacts(id),
    shared_iam_user_id          bigint NOT NULL REFERENCES iam_users(id),
    created_at                  timestamp with time zone,
    updated_at                  timestamp with time zone,
    deleted_at                  timestamp with time zone,
    iam_account_id              bigint NOT NULL REFERENCES iam_accounts(id),
    iam_user_id                 bigint NOT NULL REFERENCES iam_users(id)
);

CREATE UNIQUE INDEX IF NOT EXISTS crm_account_contact_shares_unique
    ON crm_account_contact_shares (crm_account_contact_id, shared_iam_user_id) WHERE deleted_at IS NULL;
CREATE UNIQUE INDEX IF NOT EXISTS crm_account_contact_shares_uuid_idx ON crm_account_contact_shares (uuid);
CREATE INDEX IF NOT EXISTS crm_account_contact_shares_user_idx ON crm_account_contact_shares (shared_iam_user_id);

COMMIT;
