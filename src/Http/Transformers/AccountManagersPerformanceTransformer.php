<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\AccountManagersPerformance;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractAccountManagersPerformanceTransformer;

/**
 * Class AccountManagersPerformanceTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class AccountManagersPerformanceTransformer extends AbstractAccountManagersPerformanceTransformer
{

    /**
     * @param AccountManagersPerformance $model
     *
     * @return array
     */
    public function transform(AccountManagersPerformance $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('AccountManagersPerformance', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('AccountManagersPerformance', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
