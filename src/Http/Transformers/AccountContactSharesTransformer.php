<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\AccountContactShares;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractAccountContactSharesTransformer;

/**
 * Class AccountContactSharesTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class AccountContactSharesTransformer extends AbstractAccountContactSharesTransformer
{

    /**
     * @param AccountContactShares $model
     *
     * @return array
     */
    public function transform(AccountContactShares $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('AccountContactShares', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('AccountContactShares', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
