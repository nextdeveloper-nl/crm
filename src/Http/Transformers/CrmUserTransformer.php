<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\CrmUser;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractCrmUserTransformer;

/**
 * Class CrmUserTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class CrmUserTransformer extends AbstractCrmUserTransformer {

    /**
     * @param CrmUser $model
     *
     * @return array
     */
    public function transform(CrmUser $model) {
        $transformed = Cache::get(
            CacheHelper::getKey('CrmUser', $model->uuid, 'Transformed')
        );

        if($transformed)
            return $transformed;

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('CrmUser', $model->uuid, 'Transformed'),
            $transformed
        );

        return parent::transform($model);
    }
}
