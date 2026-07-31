-- PostgreSQL

CREATE TABLE crm_meetings (
    id                       bigint NOT NULL DEFAULT nextval('crm_meetings_id_seq'::regclass),
    uuid                     uuid DEFAULT gen_random_uuid(),
    agenda_calendar_item_id  bigint,
    meeting_note             text NOT NULL, -- [ui:markdown]
    outcome                  text NOT NULL, -- [ui:markdown]
    iam_user_id              bigint NOT NULL,
    iam_account_id           bigint NOT NULL,
    crm_account_id           bigint NOT NULL,
    created_at               timestamp with time zone,
    updated_at               timestamp with time zone,
    deleted_at               timestamp with time zone,
    customer_requirements    text,
    suggestions              text,
    crm_opportunity_id       bigint,
    CONSTRAINT crm_meetings_pkey PRIMARY KEY (id)
);
