<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\RegulatoryCompliances;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractRegulatoryCompliancesTransformer;

/**
 * Class RegulatoryCompliancesTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class RegulatoryCompliancesTransformer extends AbstractRegulatoryCompliancesTransformer
{

    /**
     * @param RegulatoryCompliances $model
     *
     * @return array
     */
    public function transform(RegulatoryCompliances $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('RegulatoryCompliances', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('RegulatoryCompliances', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
