<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\QuotesPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractQuotesPerspectiveTransformer;

/**
 * Class QuotesPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class QuotesPerspectiveTransformer extends AbstractQuotesPerspectiveTransformer
{

    /**
     * @param QuotesPerspective $model
     *
     * @return array
     */
    public function transform(QuotesPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('QuotesPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        $description = \GrahamCampbell\Markdown\Facades\Markdown::convertToHtml($model->description);
        $transformed['description'] = $description->getContent();

        Cache::set(
            CacheHelper::getKey('QuotesPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
