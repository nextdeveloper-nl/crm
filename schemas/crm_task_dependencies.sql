-- PostgreSQL
-- Links between tasks: the successor waits for the predecessor (leo4#1245).

CREATE TABLE crm_task_dependencies (
    id                  bigserial PRIMARY KEY,
    uuid                uuid NOT NULL DEFAULT gen_random_uuid(),
    predecessor_task_id bigint NOT NULL REFERENCES crm_tasks(id) ON DELETE CASCADE,
    successor_task_id   bigint NOT NULL REFERENCES crm_tasks(id) ON DELETE CASCADE,
    type                text NOT NULL DEFAULT 'finish_to_start',
    lag_days            integer NOT NULL DEFAULT 0,
    iam_account_id      bigint NOT NULL REFERENCES iam_accounts(id),
    iam_user_id         bigint NOT NULL REFERENCES iam_users(id),
    created_at          timestamp with time zone,
    updated_at          timestamp with time zone,
    deleted_at          timestamp with time zone,
    CONSTRAINT crm_task_dependencies_type_check
        CHECK (type IN ('finish_to_start', 'start_to_start', 'finish_to_finish', 'start_to_finish')),
    CONSTRAINT crm_task_dependencies_not_self_check CHECK (predecessor_task_id <> successor_task_id)
);

CREATE UNIQUE INDEX crm_task_dependencies_uuid_idx ON crm_task_dependencies (uuid);
CREATE UNIQUE INDEX crm_task_dependencies_pair_idx
    ON crm_task_dependencies (predecessor_task_id, successor_task_id) WHERE deleted_at IS NULL;
CREATE INDEX crm_task_dependencies_successor_idx
    ON crm_task_dependencies (successor_task_id) WHERE deleted_at IS NULL;
CREATE INDEX crm_task_dependencies_account_idx
    ON crm_task_dependencies (iam_account_id) WHERE deleted_at IS NULL;

COMMENT ON COLUMN crm_task_dependencies.predecessor_task_id IS '[!model] The task that comes first.';
COMMENT ON COLUMN crm_task_dependencies.successor_task_id IS '[!model] The task that waits for it.';
