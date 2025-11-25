<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\MonthlyNewAccountsPerDistPerformance;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractMonthlyNewAccountsPerDistPerformanceTransformer;

/**
 * Class MonthlyNewAccountsPerDistPerformanceTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class MonthlyNewAccountsPerDistPerformanceTransformer extends AbstractMonthlyNewAccountsPerDistPerformanceTransformer
{

    /**
     * @param MonthlyNewAccountsPerDistPerformance $model
     *
     * @return array
     */
    public function transform(MonthlyNewAccountsPerDistPerformance $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('MonthlyNewAccountsPerDistPerformance', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('MonthlyNewAccountsPerDistPerformance', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
