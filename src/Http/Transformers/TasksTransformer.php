<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\Tasks;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractTasksTransformer;

/**
 * Class TasksTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class TasksTransformer extends AbstractTasksTransformer
{

    /**
     * @param Tasks $model
     *
     * @return array
     */
    public function transform(Tasks $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('Tasks', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        if($model->object_Type) {
            $object = $model->getObject();

            $transformed['object_type'] = $model->object_type;
            $transformed['object_id'] = $object ? $object->uuid : null;
        }

        Cache::set(
            CacheHelper::getKey('Tasks', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
