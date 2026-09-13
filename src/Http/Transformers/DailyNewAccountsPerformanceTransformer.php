<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\DailyNewAccountsPerformance;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractDailyNewAccountsPerformanceTransformer;

/**
 * Class DailyNewAccountsPerformanceTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class DailyNewAccountsPerformanceTransformer extends AbstractDailyNewAccountsPerformanceTransformer
{

    /**
     * @param DailyNewAccountsPerformance $model
     *
     * @return array
     */
    public function transform(DailyNewAccountsPerformance $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('DailyNewAccountsPerformance', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('DailyNewAccountsPerformance', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
