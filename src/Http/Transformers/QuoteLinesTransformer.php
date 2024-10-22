<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\QuoteLines;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractQuoteLinesTransformer;

/**
 * Class QuoteLinesTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class QuoteLinesTransformer extends AbstractQuoteLinesTransformer
{

    /**
     * @param QuoteLines $model
     *
     * @return array
     */
    public function transform(QuoteLines $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('QuoteLines', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('QuoteLines', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
