<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\WeeklyNewAccountsPerformance;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractWeeklyNewAccountsPerformanceTransformer;

/**
 * Class WeeklyNewAccountsPerformanceTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class WeeklyNewAccountsPerformanceTransformer extends AbstractWeeklyNewAccountsPerformanceTransformer
{

    /**
     * @param WeeklyNewAccountsPerformance $model
     *
     * @return array
     */
    public function transform(WeeklyNewAccountsPerformance $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('WeeklyNewAccountsPerformance', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('WeeklyNewAccountsPerformance', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
