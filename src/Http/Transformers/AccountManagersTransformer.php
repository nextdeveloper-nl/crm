<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\AccountManagers;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractAccountManagersTransformer;

/**
 * Class AccountManagersTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class AccountManagersTransformer extends AbstractAccountManagersTransformer
{

    /**
     * @param AccountManagers $model
     *
     * @return array
     */
    public function transform(AccountManagers $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('AccountManagers', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('AccountManagers', $model->uuid, 'Transformed'),
            $transformed
        );

        return parent::transform($model);
    }
}
