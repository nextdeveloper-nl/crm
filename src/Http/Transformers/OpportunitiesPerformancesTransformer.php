<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\OpportunitiesPerformances;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractOpportunitiesPerformancesTransformer;

/**
 * Class OpportunitiesPerformancesTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class OpportunitiesPerformancesTransformer extends AbstractOpportunitiesPerformancesTransformer
{

    /**
     * @param OpportunitiesPerformances $model
     *
     * @return array
     */
    public function transform(OpportunitiesPerformances $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('OpportunitiesPerformances', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('OpportunitiesPerformances', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
