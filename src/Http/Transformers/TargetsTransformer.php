<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\Targets;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractTargetsTransformer;

/**
 * Class TargetsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class TargetsTransformer extends AbstractTargetsTransformer
{

    /**
     * @param Targets $model
     *
     * @return array
     */
    public function transform(Targets $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('Targets', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('Targets', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
