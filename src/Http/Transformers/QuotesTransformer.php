<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\Quotes;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractQuotesTransformer;

/**
 * Class QuotesTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class QuotesTransformer extends AbstractQuotesTransformer
{

    /**
     * @param Quotes $model
     *
     * @return array
     */
    public function transform(Quotes $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('Quotes', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        $transformed['description'] = \GrahamCampbell\Markdown\Facades\Markdown::convertToHtml($model->description);

        Cache::set(
            CacheHelper::getKey('Quotes', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
