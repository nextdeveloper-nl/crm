<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\LeoActiveCustomersPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractLeoActiveCustomersPerspectiveTransformer;

/**
 * Class LeoActiveCustomersPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class LeoActiveCustomersPerspectiveTransformer extends AbstractLeoActiveCustomersPerspectiveTransformer
{

    /**
     * @param LeoActiveCustomersPerspective $model
     *
     * @return array
     */
    public function transform(LeoActiveCustomersPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('LeoActiveCustomersPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('LeoActiveCustomersPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
