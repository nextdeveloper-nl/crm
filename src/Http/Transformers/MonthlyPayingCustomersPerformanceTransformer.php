<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\MonthlyPayingCustomersPerformance;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractMonthlyPayingCustomersPerformanceTransformer;

/**
 * Class MonthlyPayingCustomersPerformanceTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class MonthlyPayingCustomersPerformanceTransformer extends AbstractMonthlyPayingCustomersPerformanceTransformer
{

    /**
     * @param MonthlyPayingCustomersPerformance $model
     *
     * @return array
     */
    public function transform(MonthlyPayingCustomersPerformance $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('MonthlyPayingCustomersPerformance', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('MonthlyPayingCustomersPerformance', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
