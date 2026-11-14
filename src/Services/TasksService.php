<?php

namespace NextDeveloper\CRM\Services;

use App\Helpers\ObjectHelper;
use NextDeveloper\CRM\Database\Filters\TasksQueryFilter;
use NextDeveloper\CRM\Services\AbstractServices\AbstractTasksService;

/**
 * This class is responsible from managing the data for Tasks
 *
 * Class TasksService.
 *
 * @package NextDeveloper\CRM\Database\Models
 */
class TasksService extends AbstractTasksService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    public static function get(?TasksQueryFilter $filter = null, array $params = []): \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        if(array_key_exists('objectType', $params)) {
            if(!array_key_exists('objectId', $params)) {
                throw new \Exception("When you are filtering by object_type, you must provide also object_id.");
            }

            $objectType = request()->get('objectType');
            $objectType = explode('\\', $objectType);
            $objectType = $objectType[0] . '\\' . $objectType[1] . '\\Database\\Models\\' . $objectType[2];

            $objectId = request()->get('objectId');
            $object = app($objectType)->where('uuid', $objectId)->first();

            $list = \NextDeveloper\CRM\Database\Models\Tasks::where('object_type', '=', $objectType)
                ->where('object_id', '=', $object->id)
                ->get();

            return $list;
        }

        return parent::get($filter, $params);
    }

    public static function create($data)
    {
        if(array_key_exists('object_type', $data) && array_key_exists('object_id', $data)) {
            $object = ObjectHelper::getObject($data['object_type'], $data['object_id']);

            $data['object_type'] = get_class($object);
            $data['object_id'] = $object->id;
        }

        if (!isset($data['description'])) {
            $data['description'] = '';
        }

        // Schedule fields, parent and milestone rules (leo4#1245).
        $data = TaskScheduleService::prepare(null, $data);

        $task = parent::create($data);

        TaskScheduleService::rollUp($task?->crm_task_id);

        return $task;
    }

    public static function update($id, array $data)
    {
        $existing = \NextDeveloper\CRM\Database\Models\Tasks::where('uuid', $id)->first();

        // crm_tasks.description is NOT NULL, and an emptied textarea arrives as null.
        if (array_key_exists('description', $data) && $data['description'] === null) {
            $data['description'] = '';
        }

        if ($existing) {
            $data = TaskScheduleService::prepare($existing, $data);
        }

        $previousParentId = $existing?->crm_task_id;

        $task = parent::update($id, $data);

        // A subtask's change rolls up into its parent, and into the old parent when it moved (leo4#1245).
        TaskScheduleService::rollUp($task?->crm_task_id);

        if ($previousParentId && $previousParentId !== $task?->crm_task_id) {
            TaskScheduleService::rollUp($previousParentId);
        }

        self::invalidateBoards($task?->id);

        return $task;
    }

    public static function delete($id)
    {
        $task = \NextDeveloper\CRM\Database\Models\Tasks::withoutGlobalScopes()->where('uuid', $id)->first(['id', 'crm_task_id']);

        $result = parent::delete($id);

        if ($task) {
            // Subtasks of a deleted task move up to its parent (or become top-level tasks).
            \Illuminate\Support\Facades\DB::table('crm_tasks')
                ->where('crm_task_id', $task->id)
                ->whereNull('deleted_at')
                ->update(['crm_task_id' => $task->crm_task_id, 'updated_at' => now()]);

            TaskScheduleService::rollUp($task->crm_task_id);
        }

        self::invalidateBoards($task?->id);

        return $result;
    }

    /**
     * Project boards cache their payload (leo4#1242): drop the snapshot of every pipeline
     * that shows this task, so an edit appears without waiting for the cache to expire.
     */
    public static function invalidateBoards(?int $taskId): void
    {
        $flowPipelines = '\NextDeveloper\Flow\Services\PipelinesService';

        if (!$taskId || !class_exists($flowPipelines)) {
            return;
        }

        $pipelineIds = \Illuminate\Support\Facades\DB::table('flow_items')
            ->where('object_type', 'NextDeveloper\\CRM\\Tasks')
            ->where('object_id', $taskId)
            ->whereNull('deleted_at')
            ->distinct()
            ->pluck('flow_pipeline_id');

        foreach ($pipelineIds as $pipelineId) {
            $flowPipelines::invalidateBoardByPipelineId($pipelineId);
        }
    }
}
