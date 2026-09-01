-- PostgreSQL

CREATE TABLE crm_target_users (
    crm_target_id  bigint NOT NULL,
    crm_user_id    bigint NOT NULL,
    created_at     timestamp with time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at     timestamp with time zone DEFAULT CURRENT_TIMESTAMP
);
