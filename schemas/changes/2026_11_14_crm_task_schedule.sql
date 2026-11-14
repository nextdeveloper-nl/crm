-- PostgreSQL
-- leo4#1245 / panel-v6.2#277: schedule fields, subtasks and dependencies for tasks (Project pipelines' Gantt view).
--
-- crm_tasks gains:
--   start_date       when the work starts (due_date is when it ends)
--   progress         0-100; a parent's value is the duration-weighted average of its subtasks
--   crm_task_id      the parent task (self reference). Named by the generator convention: any *_id column
--                    is an FK named after its table, as common_categories.common_category_id is.
--   is_milestone     a zero-length point in the plan (start_date = due_date)
--   sort_order       manual order among siblings
--   estimated_hours  optional effort estimate
--
-- crm_task_dependencies: predecessor -> successor links. finish_to_start is the one the panel draws; the schema
-- allows the other three types for later scheduling work.

BEGIN;

ALTER TABLE crm_tasks ADD COLUMN IF NOT EXISTS start_date timestamp with time zone;
ALTER TABLE crm_tasks ADD COLUMN IF NOT EXISTS progress integer NOT NULL DEFAULT 0;
ALTER TABLE crm_tasks ADD COLUMN IF NOT EXISTS crm_task_id bigint;
ALTER TABLE crm_tasks ADD COLUMN IF NOT EXISTS is_milestone boolean NOT NULL DEFAULT false;
ALTER TABLE crm_tasks ADD COLUMN IF NOT EXISTS sort_order integer NOT NULL DEFAULT 0;
ALTER TABLE crm_tasks ADD COLUMN IF NOT EXISTS estimated_hours double precision;

ALTER TABLE crm_tasks DROP CONSTRAINT IF EXISTS crm_tasks_crm_task_id_fkey;
ALTER TABLE crm_tasks ADD CONSTRAINT crm_tasks_crm_task_id_fkey
    FOREIGN KEY (crm_task_id) REFERENCES crm_tasks(id) ON DELETE SET NULL;

ALTER TABLE crm_tasks DROP CONSTRAINT IF EXISTS crm_tasks_progress_check;
ALTER TABLE crm_tasks ADD CONSTRAINT crm_tasks_progress_check CHECK (progress BETWEEN 0 AND 100);

ALTER TABLE crm_tasks DROP CONSTRAINT IF EXISTS crm_tasks_schedule_order_check;
ALTER TABLE crm_tasks ADD CONSTRAINT crm_tasks_schedule_order_check
    CHECK (start_date IS NULL OR due_date IS NULL OR due_date >= start_date);

ALTER TABLE crm_tasks DROP CONSTRAINT IF EXISTS crm_tasks_not_own_parent_check;
ALTER TABLE crm_tasks ADD CONSTRAINT crm_tasks_not_own_parent_check CHECK (crm_task_id IS NULL OR crm_task_id <> id);

CREATE INDEX IF NOT EXISTS crm_tasks_crm_task_id_idx ON crm_tasks (crm_task_id) WHERE deleted_at IS NULL;

COMMENT ON COLUMN crm_tasks.crm_task_id IS 'The parent task. A task with subtasks takes its dates and progress from them.';
COMMENT ON COLUMN crm_tasks.progress IS 'Percent done, 0-100.';

CREATE TABLE IF NOT EXISTS crm_task_dependencies (
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

CREATE UNIQUE INDEX IF NOT EXISTS crm_task_dependencies_uuid_idx ON crm_task_dependencies (uuid);
CREATE UNIQUE INDEX IF NOT EXISTS crm_task_dependencies_pair_idx
    ON crm_task_dependencies (predecessor_task_id, successor_task_id) WHERE deleted_at IS NULL;
CREATE INDEX IF NOT EXISTS crm_task_dependencies_successor_idx
    ON crm_task_dependencies (successor_task_id) WHERE deleted_at IS NULL;
CREATE INDEX IF NOT EXISTS crm_task_dependencies_account_idx
    ON crm_task_dependencies (iam_account_id) WHERE deleted_at IS NULL;

COMMENT ON COLUMN crm_task_dependencies.predecessor_task_id IS '[!model] The task that comes first.';
COMMENT ON COLUMN crm_task_dependencies.successor_task_id IS '[!model] The task that waits for it.';

COMMIT;

-- Rollback:
-- BEGIN;
-- DROP TABLE IF EXISTS crm_task_dependencies;
-- ALTER TABLE crm_tasks DROP CONSTRAINT IF EXISTS crm_tasks_not_own_parent_check;
-- ALTER TABLE crm_tasks DROP CONSTRAINT IF EXISTS crm_tasks_schedule_order_check;
-- ALTER TABLE crm_tasks DROP CONSTRAINT IF EXISTS crm_tasks_progress_check;
-- ALTER TABLE crm_tasks DROP CONSTRAINT IF EXISTS crm_tasks_crm_task_id_fkey;
-- DROP INDEX IF EXISTS crm_tasks_crm_task_id_idx;
-- ALTER TABLE crm_tasks DROP COLUMN IF EXISTS estimated_hours, DROP COLUMN IF EXISTS sort_order,
--     DROP COLUMN IF EXISTS is_milestone, DROP COLUMN IF EXISTS crm_task_id, DROP COLUMN IF EXISTS progress,
--     DROP COLUMN IF EXISTS start_date;
-- COMMIT;
