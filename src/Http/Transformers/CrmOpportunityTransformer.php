<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\CrmOpportunity;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractCrmOpportunityTransformer;

/**
 * Class CrmOpportunityTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class CrmOpportunityTransformer extends AbstractCrmOpportunityTransformer {

    /**
     * @param CrmOpportunity $model
     *
     * @return array
     */
    public function transform(CrmOpportunity $model) {
        $transformed = Cache::get(
            CacheHelper::getKey('CrmOpportunity', $model->uuid, 'Transformed')
        );

        if($transformed)
            return $transformed;

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('CrmOpportunity', $model->uuid, 'Transformed'),
            $transformed
        );

        return parent::transform($model);
    }
}
