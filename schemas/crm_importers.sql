-- PostgreSQL
-- Generic lead importer connections. One row per customer (iam_account) connection to a lead source.
-- Source-agnostic: source_type tells which adapter feeds it (e.g. "facebook"); nothing here is Facebook specific.

CREATE TABLE crm_importers (
    id                     bigint NOT NULL DEFAULT nextval('crm_importers_id_seq'::regclass),
    uuid                   uuid NOT NULL DEFAULT gen_random_uuid(),
    name                   text NOT NULL,
    source_type            text NOT NULL, -- Adapter key, e.g. "facebook". Registered in the importer adapter list.
    status                 text NOT NULL DEFAULT 'active', -- active, paused, error
    config                 json, -- Mapping: flow_pipeline_id, flow_stage_id, opportunity_type, field_map, dedupe_window_hours, default_country
    credentials_enc        text, -- Encrypted source credentials (if the source needs its own). Never returned by the API; write-only from clients.
    cursor                 text, -- Poll watermark for backfill syncs
    last_synced_at         timestamp with time zone,
    last_error             text,
    object_type            text, -- Optional link to the source object, full model class e.g. NextDeveloper\Communication\Database\Models\Channels
    object_id              bigint,
    iam_account_id         bigint NOT NULL,
    iam_user_id            bigint NOT NULL,
    created_at             timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at             timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at             timestamp with time zone,
    CONSTRAINT crm_importers_pkey PRIMARY KEY (id),
    CONSTRAINT crm_importers_iam_account_fk FOREIGN KEY (iam_account_id) REFERENCES iam_accounts (id),
    CONSTRAINT crm_importers_iam_user_fk FOREIGN KEY (iam_user_id) REFERENCES iam_users (id)
);

CREATE UNIQUE INDEX crm_importers_uuid_idx ON crm_importers (uuid);
CREATE INDEX crm_importers_source_status_idx ON crm_importers (source_type, status) WHERE deleted_at IS NULL;
CREATE INDEX crm_importers_object_idx ON crm_importers (object_type, object_id);
