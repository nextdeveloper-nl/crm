-- PostgreSQL

CREATE TABLE crm_accounts (
    id                             bigint NOT NULL DEFAULT nextval('crm_accounts_id_seq'::regclass),
    uuid                           uuid DEFAULT gen_random_uuid(),
    iam_account_id                 bigint NOT NULL,
    is_paying_customer             boolean DEFAULT false,
    risk_level                     smallint,
    common_city_id                 bigint,
    position                       text,
    tags                           text[] NOT NULL DEFAULT '{}'::text[],
    created_at                     timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at                     timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at                     timestamp with time zone,
    additional_information         text,
    is_suspended                   boolean DEFAULT false,
    is_service_enabled             boolean DEFAULT false,
    is_disabled                    boolean DEFAULT false,
    disabling_reason               text,
    suspension_reason              text,
    technology_rank                integer,
    is_sdr_qualified               boolean DEFAULT false,
    is_sdr_qualification_required  boolean NOT NULL DEFAULT true,
    disqualification_reason        text,
    office_phone_number            text,
    office_phone_extension         text,
    sdr_questionaire               json,
    office_email                   text,
    owner_iam_account_id           bigint NOT NULL DEFAULT 5,
    is_created_by_owner            boolean NOT NULL DEFAULT false, -- owner created the customer (see schemas/changes/2026_11_14_crm_accounts_created_by_owner.sql) -- tenant that owns this CRM record (see schemas/changes/2026_11_14_crm_accounts_owner.sql)
    CONSTRAINT crm_accounts_pkey PRIMARY KEY (id),
    CONSTRAINT crm_accounts_owner_iam_account_id_fkey FOREIGN KEY (owner_iam_account_id) REFERENCES iam_accounts(id)
);

CREATE UNIQUE INDEX crm_accounts_iam_account_owner_uidx ON crm_accounts (iam_account_id, owner_iam_account_id) WHERE deleted_at IS NULL;
CREATE INDEX crm_accounts_owner_iam_account_id_idx ON crm_accounts (owner_iam_account_id);
