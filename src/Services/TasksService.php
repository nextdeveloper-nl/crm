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

    public static function get(TasksQueryFilter $filter = null, array $params = []): \Illuminate\Database\Eloquent\Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
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

        return parent::create($data);
    }
}
