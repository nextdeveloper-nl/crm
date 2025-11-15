<?php

namespace NextDeveloper\CRM\Services;

use App\Helpers\ObjectHelper;
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
