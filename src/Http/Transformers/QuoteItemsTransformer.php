<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\QuoteItems;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractQuoteItemsTransformer;

/**
 * Class QuoteItemsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class QuoteItemsTransformer extends AbstractQuoteItemsTransformer
{

    /**
     * @param QuoteItems $model
     *
     * @return array
     */
    public function transform(QuoteItems $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('QuoteItems', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('QuoteItems', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
