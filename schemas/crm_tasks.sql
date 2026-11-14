-- PostgreSQL

CREATE TABLE crm_tasks (
    id              bigint NOT NULL DEFAULT nextval('crm_tasks_id_seq'::regclass),
    uuid            uuid DEFAULT gen_random_uuid(),
    description     text NOT NULL,
    iam_user_id     bigint NOT NULL,
    crm_account_id  bigint,
    priority        integer DEFAULT 1,
    is_finished     boolean DEFAULT false,
    is_delayed      boolean DEFAULT false,
    created_at      timestamp with time zone,
    updated_at      timestamp with time zone,
    deleted_at      timestamp with time zone,
    name            text NOT NULL,
    iam_account_id  bigint NOT NULL,
    object_type     text,
    object_id       bigint,
    due_date        timestamp with time zone,
    start_date      timestamp with time zone,
    progress        integer NOT NULL DEFAULT 0,
    crm_task_id     bigint,
    is_milestone    boolean NOT NULL DEFAULT false,
    sort_order      integer NOT NULL DEFAULT 0,
    estimated_hours double precision,
    CONSTRAINT crm_tasks_pkey PRIMARY KEY (id),
    CONSTRAINT crm_tasks_crm_task_id_fkey FOREIGN KEY (crm_task_id) REFERENCES crm_tasks(id) ON DELETE SET NULL,
    CONSTRAINT crm_tasks_progress_check CHECK (progress BETWEEN 0 AND 100),
    CONSTRAINT crm_tasks_schedule_order_check CHECK (start_date IS NULL OR due_date IS NULL OR due_date >= start_date),
    CONSTRAINT crm_tasks_not_own_parent_check CHECK (crm_task_id IS NULL OR crm_task_id <> id)
);

CREATE INDEX crm_tasks_crm_task_id_idx ON crm_tasks (crm_task_id) WHERE deleted_at IS NULL;
