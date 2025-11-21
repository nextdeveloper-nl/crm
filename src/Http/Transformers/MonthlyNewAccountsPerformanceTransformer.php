<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\MonthlyNewAccountsPerformance;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractMonthlyNewAccountsPerformanceTransformer;

/**
 * Class MonthlyNewAccountsPerformanceTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class MonthlyNewAccountsPerformanceTransformer extends AbstractMonthlyNewAccountsPerformanceTransformer
{

    /**
     * @param MonthlyNewAccountsPerformance $model
     *
     * @return array
     */
    public function transform(MonthlyNewAccountsPerformance $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('MonthlyNewAccountsPerformance', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('MonthlyNewAccountsPerformance', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
