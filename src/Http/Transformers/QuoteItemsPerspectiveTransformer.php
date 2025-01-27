<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\QuoteItemsPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractQuoteItemsPerspectiveTransformer;

/**
 * Class QuoteItemsPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class QuoteItemsPerspectiveTransformer extends AbstractQuoteItemsPerspectiveTransformer
{

    /**
     * @param QuoteItemsPerspective $model
     *
     * @return array
     */
    public function transform(QuoteItemsPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('QuoteItemsPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('QuoteItemsPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
