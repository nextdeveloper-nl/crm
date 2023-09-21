<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\UserManagers;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractUserManagersTransformer;

/**
 * Class UserManagersTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class UserManagersTransformer extends AbstractUserManagersTransformer
{

    /**
     * @param UserManagers $model
     *
     * @return array
     */
    public function transform(UserManagers $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('UserManagers', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('UserManagers', $model->uuid, 'Transformed'),
            $transformed
        );

        return parent::transform($model);
    }
}
