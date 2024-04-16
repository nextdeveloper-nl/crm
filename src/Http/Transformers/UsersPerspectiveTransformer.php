<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\UsersPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractUsersPerspectiveTransformer;

/**
 * Class UsersPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class UsersPerspectiveTransformer extends AbstractUsersPerspectiveTransformer
{

    /**
     * @param UsersPerspective $model
     *
     * @return array
     */
    public function transform(UsersPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('UsersPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('UsersPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
