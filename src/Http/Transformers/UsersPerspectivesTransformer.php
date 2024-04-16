<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\UsersPerspectives;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractUsersPerspectivesTransformer;

/**
 * Class UsersPerspectivesTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class UsersPerspectivesTransformer extends AbstractUsersPerspectivesTransformer
{

    /**
     * @param UsersPerspectives $model
     *
     * @return array
     */
    public function transform(UsersPerspectives $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('UsersPerspectives', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('UsersPerspectives', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
