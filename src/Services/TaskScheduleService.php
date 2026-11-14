<?php

namespace NextDeveloper\CRM\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\CRM\Database\Models\TaskDependencies;
use NextDeveloper\CRM\Database\Models\Tasks;
use NextDeveloper\Events\Services\Events;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * Schedules of tasks for the Gantt view of Project pipelines (leo4#1245): start and due dates,
 * progress, subtasks and dependencies.
 *
 * Roll-up rule: a task with subtasks does not keep its own schedule. Its start_date is the
 * earliest start of its subtasks, its due_date the latest due, and its progress the
 * duration-weighted average of their progress (a subtask without both dates weighs one day).
 * The rolled-up values are stored on the parent every time a subtask changes, so every reader
 * (task API, board, Gantt) sees the same numbers. Editing a parent's dates or progress
 * directly is refused.
 */
class TaskScheduleService
{
    public const DEPENDENCY_TYPES = ['finish_to_start', 'start_to_start', 'finish_to_finish', 'start_to_finish'];

    /** Longest parent chain walked; deeper chains are treated as broken data, not followed. */
    private const MAX_DEPTH = 50;

    /**
     * Checks a create or update of a task's schedule fields and returns the data to save.
     * Converts crm_task_id (parent uuid) to its id; a milestone gets start = due.
     *
     * @param  Tasks|null  $task  the task being updated, or null on create
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     *
     * @throws NotAllowedException
     */
    public static function prepare(?Tasks $task, array $data): array
    {
        if (array_key_exists('crm_task_id', $data)) {
            $data['crm_task_id'] = self::resolveParentId($task, $data['crm_task_id']);
        }

        $scheduleKeys = array_intersect(['start_date', 'due_date', 'progress'], array_keys($data));

        if ($task && $scheduleKeys && self::hasSubtasks($task->id)) {
            $changed = array_filter($scheduleKeys, fn ($key) => !self::sameValue($task->{$key}, $data[$key]));

            if ($changed) {
                throw new NotAllowedException('The dates and progress of a task with subtasks follow its subtasks. Change the subtasks instead.');
            }

            foreach ($scheduleKeys as $key) {
                unset($data[$key]);
            }
        }

        $isMilestone = (bool) ($data['is_milestone'] ?? $task?->is_milestone ?? false);

        $start = array_key_exists('start_date', $data) ? $data['start_date'] : $task?->start_date;
        $due = array_key_exists('due_date', $data) ? $data['due_date'] : $task?->due_date;

        if ($isMilestone) {
            // A milestone is a point in time: the date that was just set wins.
            $point = array_key_exists('due_date', $data) ? $due : (array_key_exists('start_date', $data) ? $start : ($due ?? $start));

            if ($point !== null) {
                $data['start_date'] = $point;
                $data['due_date'] = $point;
                $start = $due = $point;
            }
        }

        if ($start !== null && $due !== null && Carbon::parse($due)->lt(Carbon::parse($start))) {
            throw new NotAllowedException('The due date can not be before the start date.');
        }

        if (array_key_exists('progress', $data) && $data['progress'] !== null) {
            $data['progress'] = max(0, min(100, (int) $data['progress']));
        }

        return $data;
    }

    /**
     * Stores the roll-up on the given parent and on every ancestor above it.
     */
    public static function rollUp(?int $parentId): void
    {
        $depth = 0;

        while ($parentId && $depth++ < self::MAX_DEPTH) {
            $parent = DB::table('crm_tasks')->where('id', $parentId)->first(['id', 'uuid', 'crm_task_id', 'start_date', 'due_date', 'progress']);

            if (!$parent) {
                return;
            }

            $children = DB::table('crm_tasks')
                ->where('crm_task_id', $parentId)
                ->whereNull('deleted_at')
                ->get(['start_date', 'due_date', 'progress'])
                ->map(fn ($child) => (array) $child)
                ->all();

            if ($children) {
                $starts = array_filter(array_column($children, 'start_date'));
                $dues = array_filter(array_column($children, 'due_date'));

                $rolled = [
                    'start_date' => $starts ? min(array_map(fn ($d) => Carbon::parse($d), $starts)) : null,
                    'due_date'   => $dues ? max(array_map(fn ($d) => Carbon::parse($d), $dues)) : null,
                    'progress'   => self::weightedProgress($children),
                ];

                $isSame = self::sameValue($parent->start_date, $rolled['start_date'])
                    && self::sameValue($parent->due_date, $rolled['due_date'])
                    && (int) $parent->progress === $rolled['progress'];

                if (!$isSame) {
                    DB::table('crm_tasks')->where('id', $parentId)->update($rolled + ['updated_at' => now()]);
                    CacheHelper::deleteKeys('Tasks', $parent->uuid);
                    TasksService::invalidateBoards($parentId);
                }
            }

            $parentId = $parent->crm_task_id;
        }
    }

    /**
     * Duration-weighted average progress. A subtask without both dates weighs one day.
     *
     * @param  array<int, array{start_date: mixed, due_date: mixed, progress: mixed}>  $children
     */
    public static function weightedProgress(array $children): int
    {
        $total = 0.0;
        $done = 0.0;

        foreach ($children as $child) {
            $weight = 1.0;

            if (!empty($child['start_date']) && !empty($child['due_date'])) {
                $weight = max(1.0, Carbon::parse($child['start_date'])->diffInDays(Carbon::parse($child['due_date'])) + 1);
            }

            $total += $weight;
            $done += $weight * max(0, min(100, (int) ($child['progress'] ?? 0)));
        }

        return $total > 0 ? (int) round($done / $total) : 0;
    }

    /**
     * Whether adding the link $from -> $to closes a loop, i.e. $from is already reachable from $to.
     *
     * @param  array<int, array{0: int, 1: int}>  $edges  existing [predecessor, successor] pairs
     */
    public static function wouldCreateCycle(array $edges, int $from, int $to): bool
    {
        if ($from === $to) {
            return true;
        }

        $next = [];
        foreach ($edges as [$predecessor, $successor]) {
            $next[$predecessor][] = $successor;
        }

        $queue = [$to];
        $seen = [$to => true];

        while ($queue) {
            $node = array_shift($queue);

            foreach ($next[$node] ?? [] as $successor) {
                if ($successor === $from) {
                    return true;
                }

                if (!isset($seen[$successor])) {
                    $seen[$successor] = true;
                    $queue[] = $successor;
                }
            }
        }

        return false;
    }

    /**
     * Links two tasks: the successor waits for the predecessor.
     *
     * @throws NotAllowedException
     */
    public static function addDependency(string $predecessorUuid, string $successorUuid, string $type = 'finish_to_start', int $lagDays = 0): TaskDependencies
    {
        $predecessor = self::findTask($predecessorUuid);
        $successor = self::findTask($successorUuid);

        if (!in_array($type, self::DEPENDENCY_TYPES, true)) {
            throw new NotAllowedException('This kind of link is not supported.');
        }

        if ($predecessor->id === $successor->id) {
            throw new NotAllowedException('A task can not depend on itself.');
        }

        if ($predecessor->iam_account_id !== $successor->iam_account_id) {
            throw new NotAllowedException('Both tasks must belong to the same account.');
        }

        if (self::isAncestor($predecessor->id, $successor->id) || self::isAncestor($successor->id, $predecessor->id)) {
            throw new NotAllowedException('A task can not depend on its own subtask or on the task it belongs to.');
        }

        self::assertCanEdit($successor);

        $edges = TaskDependencies::query()
            ->where('iam_account_id', $successor->iam_account_id)
            ->get(['predecessor_task_id', 'successor_task_id'])
            ->map(fn ($link) => [$link->predecessor_task_id, $link->successor_task_id])
            ->all();

        if (in_array([$predecessor->id, $successor->id], $edges, true)) {
            throw new NotAllowedException('"'.$successor->name.'" already waits for "'.$predecessor->name.'".');
        }

        if (self::wouldCreateCycle($edges, $predecessor->id, $successor->id)) {
            throw new NotAllowedException('This link would make a loop: "'.$predecessor->name.'" already waits for "'.$successor->name.'", directly or through other tasks.');
        }

        $link = TaskDependencies::create([
            'predecessor_task_id' => $predecessor->id,
            'successor_task_id'   => $successor->id,
            'type'                => $type,
            'lag_days'            => $lagDays,
            'iam_account_id'      => $successor->iam_account_id,
            'iam_user_id'         => UserHelper::me()->id,
        ])->fresh();

        self::announce($successor);

        return $link;
    }

    /**
     * Removes a link. Only someone who may edit the waiting task can remove it.
     *
     * @throws NotAllowedException
     */
    public static function removeDependency(string $uuid): void
    {
        $link = TaskDependencies::query()
            ->where('uuid', $uuid)
            ->where('iam_account_id', UserHelper::currentAccount()->id)
            ->first();

        if (!$link) {
            throw new NotAllowedException('We can not find this link.');
        }

        $successor = Tasks::where('id', $link->successor_task_id)->first();

        if (!$successor) {
            throw new NotAllowedException('We can not find this link.');
        }

        self::assertCanEdit($successor);

        $link->delete();

        self::announce($successor);
    }

    /**
     * Links whose both ends are among the given tasks, for the Gantt view.
     *
     * @param  array<int, int>  $taskIds
     * @return Collection<int, TaskDependencies>
     */
    public static function dependenciesAmong(array $taskIds): Collection
    {
        if (!$taskIds) {
            return collect();
        }

        return TaskDependencies::query()
            ->whereIn('predecessor_task_id', $taskIds)
            ->whereIn('successor_task_id', $taskIds)
            ->get();
    }

    /**
     * Links of one task, both ways, for the task drawer.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function dependenciesOf(string $taskUuid): array
    {
        $task = self::findTask($taskUuid);

        $links = TaskDependencies::query()
            ->where(fn ($q) => $q->where('predecessor_task_id', $task->id)->orWhere('successor_task_id', $task->id))
            ->get();

        $tasks = Tasks::withoutGlobalScopes()
            ->whereIn('id', $links->pluck('predecessor_task_id')->merge($links->pluck('successor_task_id'))->unique()->values())
            ->get(['id', 'uuid', 'name'])
            ->keyBy('id');

        return $links->map(fn (TaskDependencies $link) => [
            'id'          => $link->uuid,
            'predecessor' => ['id' => $tasks->get($link->predecessor_task_id)?->uuid, 'name' => $tasks->get($link->predecessor_task_id)?->name],
            'successor'   => ['id' => $tasks->get($link->successor_task_id)?->uuid, 'name' => $tasks->get($link->successor_task_id)?->name],
            'type'        => $link->type,
            'lag_days'    => $link->lag_days,
        ])->values()->all();
    }

    /**
     * Saves dates and progress of several tasks at once (a drag on the Gantt). All or nothing.
     *
     * @param  array<int, array{id: string, start_date?: ?string, due_date?: ?string, progress?: ?int}>  $rows
     * @return array<int, Tasks>
     */
    public static function bulkSchedule(array $rows): array
    {
        return DB::transaction(function () use ($rows) {
            $saved = [];

            foreach ($rows as $row) {
                $data = array_intersect_key($row, array_flip(['start_date', 'due_date', 'progress']));
                $saved[] = TasksService::update($row['id'], $data);
            }

            return $saved;
        });
    }

    public static function hasSubtasks(int $taskId): bool
    {
        return DB::table('crm_tasks')->where('crm_task_id', $taskId)->whereNull('deleted_at')->exists();
    }

    /**
     * Whether $ancestorId is $taskId's parent, grandparent, and so on.
     */
    public static function isAncestor(int $ancestorId, int $taskId): bool
    {
        $current = DB::table('crm_tasks')->where('id', $taskId)->value('crm_task_id');
        $depth = 0;

        while ($current && $depth++ < self::MAX_DEPTH) {
            if ((int) $current === $ancestorId) {
                return true;
            }

            $current = DB::table('crm_tasks')->where('id', $current)->value('crm_task_id');
        }

        return false;
    }

    private static function resolveParentId(?Tasks $task, mixed $parentUuid): ?int
    {
        if ($parentUuid === null || $parentUuid === '') {
            return null;
        }

        $parent = Tasks::where('uuid', $parentUuid)->first();

        if (!$parent) {
            throw new NotAllowedException('We can not find the parent task.');
        }

        $accountId = $task?->iam_account_id ?? UserHelper::currentAccount()?->id;

        if ($parent->iam_account_id !== $accountId) {
            throw new NotAllowedException('The parent task must belong to the same account.');
        }

        if ($task && ($parent->id === $task->id || self::isAncestor($task->id, $parent->id))) {
            throw new NotAllowedException('A task can not be placed under itself or under one of its own subtasks.');
        }

        return $parent->id;
    }

    private static function findTask(string $uuid): Tasks
    {
        $task = Tasks::where('uuid', $uuid)->first();

        if (!$task) {
            throw new NotAllowedException('We can not find this task.');
        }

        return $task;
    }

    private static function assertCanEdit(Tasks $task): void
    {
        if (!UserHelper::can('update', $task)) {
            throw new NotAllowedException('You are not allowed to change this task.');
        }
    }

    /**
     * Other clients refresh the task and the boards that show it.
     */
    private static function announce(Tasks $task): void
    {
        CacheHelper::deleteKeys('Tasks', $task->uuid);
        TasksService::invalidateBoards($task->id);
        Events::fire('updated:NextDeveloper\CRM\Tasks', $task);
    }

    private static function sameValue(mixed $current, mixed $next): bool
    {
        if ($current === null || $next === null || $current === '' || $next === '') {
            return ($current === null || $current === '') && ($next === null || $next === '');
        }

        if ($current instanceof \DateTimeInterface || $next instanceof \DateTimeInterface || is_string($current)) {
            try {
                return Carbon::parse($current)->equalTo(Carbon::parse($next));
            } catch (\Throwable) {
                return (string) $current === (string) $next;
            }
        }

        return (string) $current === (string) $next;
    }
}
