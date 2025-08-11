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
        //  Since this is a performance perspective, we are not caching the transformed data

        $transformed = parent::transform($model);

        unset($transformed['id']);

        return $transformed;
    }
}
