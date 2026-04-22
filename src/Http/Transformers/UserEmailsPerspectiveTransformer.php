<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\UserEmailsPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractUserEmailsPerspectiveTransformer;

/**
 * Class UserEmailsPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class UserEmailsPerspectiveTransformer extends AbstractUserEmailsPerspectiveTransformer
{

    /**
     * @param UserEmailsPerspective $model
     *
     * @return array
     */
    public function transform(UserEmailsPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('UserEmailsPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('UserEmailsPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
