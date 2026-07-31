-- PostgreSQL

CREATE TABLE crm_quotes (
    id                     bigint NOT NULL DEFAULT nextval('crm_quotes_id_seq'::regclass),
    uuid                   uuid DEFAULT gen_random_uuid(),
    iam_account_id         bigint NOT NULL,
    iam_user_id            bigint,
    crm_opportunity_id     bigint,
    name                   text NOT NULL,
    description            text, -- [ui:markdown]
    total_amount           numeric DEFAULT 0, -- [ui:money][ro]
    detailed_amount        json, -- [ro]
    suggested_price        numeric, -- [ui:money]
    common_currency_id     bigint,
    tags                   text[] NOT NULL DEFAULT '{}'::text[],
    created_at             timestamp with time zone,
    updated_at             timestamp with time zone,
    deleted_at             timestamp with time zone,
    approval_level         approval_level DEFAULT 'draft'::approval_level,
    is_converted           boolean NOT NULL DEFAULT false, -- Indicates if the quote has been converted to an invoice
    accounting_invoice_id  bigint, -- The ID of the invoice created from this quote, if applicable
    CONSTRAINT crm_quotes_pkey PRIMARY KEY (id)
);
