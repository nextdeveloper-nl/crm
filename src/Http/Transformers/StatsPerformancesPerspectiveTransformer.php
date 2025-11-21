<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\StatsPerformancesPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractStatsPerformancesPerspectiveTransformer;

/**
 * Class StatsPerformancesPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class StatsPerformancesPerspectiveTransformer extends AbstractStatsPerformancesPerspectiveTransformer
{

    /**
     * @param StatsPerformancesPerspective $model
     *
     * @return array
     */
    public function transform(StatsPerformancesPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('StatsPerformancesPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('StatsPerformancesPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
