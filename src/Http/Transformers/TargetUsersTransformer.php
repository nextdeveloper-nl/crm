<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\TargetUsers;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractTargetUsersTransformer;

/**
 * Class TargetUsersTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class TargetUsersTransformer extends AbstractTargetUsersTransformer
{

    /**
     * @param TargetUsers $model
     *
     * @return array
     */
    public function transform(TargetUsers $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('TargetUsers', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('TargetUsers', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
