<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\QuoteLinesPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractQuoteLinesPerspectiveTransformer;

/**
 * Class QuoteLinesPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class QuoteLinesPerspectiveTransformer extends AbstractQuoteLinesPerspectiveTransformer
{

    /**
     * @param QuoteLinesPerspective $model
     *
     * @return array
     */
    public function transform(QuoteLinesPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('QuoteLinesPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('QuoteLinesPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
