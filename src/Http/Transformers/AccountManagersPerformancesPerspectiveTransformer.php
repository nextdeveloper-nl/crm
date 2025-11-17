<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\AccountManagersPerformancesPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractAccountManagersPerformancesPerspectiveTransformer;

/**
 * Class AccountManagersPerformancesPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class AccountManagersPerformancesPerspectiveTransformer extends AbstractAccountManagersPerformancesPerspectiveTransformer
{

    /**
     * @param AccountManagersPerformancesPerspective $model
     *
     * @return array
     */
    public function transform(AccountManagersPerformancesPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('AccountManagersPerformancesPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('AccountManagersPerformancesPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
