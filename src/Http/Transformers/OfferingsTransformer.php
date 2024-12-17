<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\Offerings;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractOfferingsTransformer;

/**
 * Class OfferingsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class OfferingsTransformer extends AbstractOfferingsTransformer
{

    /**
     * @param Offerings $model
     *
     * @return array
     */
    public function transform(Offerings $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('Offerings', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('Offerings', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
