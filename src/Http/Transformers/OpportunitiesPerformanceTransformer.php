<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\OpportunitiesPerformance;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractOpportunitiesPerformanceTransformer;

/**
 * Class OpportunitiesPerformanceTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class OpportunitiesPerformanceTransformer extends AbstractOpportunitiesPerformanceTransformer
{

    /**
     * @param OpportunitiesPerformance $model
     *
     * @return array
     */
    public function transform(OpportunitiesPerformance $model)
    {
//        $transformed = Cache::get(
//            CacheHelper::getKey('OpportunitiesPerformance', $model->uuid, 'Transformed')
//        );
//
//        if($transformed) {
//            return $transformed;
//        }

        $transformed = parent::transform($model);

//        Cache::set(
//            CacheHelper::getKey('OpportunitiesPerformance', $model->uuid, 'Transformed'),
//            $transformed
//        );

        return $transformed;
    }
}
