<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\AccountUsersPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractAccountUsersPerspectiveTransformer;

/**
 * Class AccountUsersPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class AccountUsersPerspectiveTransformer extends AbstractAccountUsersPerspectiveTransformer
{

    /**
     * @param AccountUsersPerspective $model
     *
     * @return array
     */
    public function transform(AccountUsersPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('AccountUsersPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('AccountUsersPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
