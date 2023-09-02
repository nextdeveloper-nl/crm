<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\CrmAccount;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractCrmAccountTransformer;

/**
 * Class CrmAccountTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class CrmAccountTransformer extends AbstractCrmAccountTransformer {

    /**
     * @param CrmAccount $model
     *
     * @return array
     */
    public function transform(CrmAccount $model) {
        $transformed = Cache::get(
            CacheHelper::getKey('CrmAccount', $model->uuid, 'Transformed')
        );

        if($transformed)
            return $transformed;

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('CrmAccount', $model->uuid, 'Transformed'),
            $transformed
        );

        return parent::transform($model);
    }
}
