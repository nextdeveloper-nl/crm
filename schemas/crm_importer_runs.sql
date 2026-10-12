-- PostgreSQL
-- One row per lead processed by a crm_importers connection. Audit trail, retry source and idempotency guard.

CREATE TABLE crm_importer_runs (
    id                     bigint NOT NULL DEFAULT nextval('crm_importer_runs_id_seq'::regclass),
    uuid                   uuid NOT NULL DEFAULT gen_random_uuid(),
    crm_importer_id        bigint NOT NULL,
    status                 text NOT NULL DEFAULT 'pending', -- pending, imported, duplicate, failed
    payload                json, -- Normalized lead as received by the importer (name, email, phone, extra fields)
    payload_hash           text NOT NULL, -- Hash of normalized source + email + phone + form; idempotency key (no source-specific ids)
    error                  text,
    object_type            text, -- Full model class of the created object, e.g. NextDeveloper\CRM\Database\Models\Opportunities
    object_id              bigint,
    imported_at            timestamp with time zone,
    iam_account_id         bigint NOT NULL, -- Owner account of the importer
    iam_user_id            bigint NOT NULL, -- Owner user of the importer
    created_at             timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at             timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at             timestamp with time zone,
    CONSTRAINT crm_importer_runs_pkey PRIMARY KEY (id),
    CONSTRAINT crm_importer_runs_importer_fk FOREIGN KEY (crm_importer_id) REFERENCES crm_importers (id),
    CONSTRAINT crm_importer_runs_iam_account_fk FOREIGN KEY (iam_account_id) REFERENCES iam_accounts (id),
    CONSTRAINT crm_importer_runs_iam_user_fk FOREIGN KEY (iam_user_id) REFERENCES iam_users (id)
);

CREATE UNIQUE INDEX crm_importer_runs_uuid_idx ON crm_importer_runs (uuid);
-- Same lead can only be successfully imported once per importer
CREATE UNIQUE INDEX crm_importer_runs_hash_idx ON crm_importer_runs (crm_importer_id, payload_hash) WHERE deleted_at IS NULL AND status IN ('pending', 'imported');
CREATE INDEX crm_importer_runs_status_idx ON crm_importer_runs (crm_importer_id, status);
