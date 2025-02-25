<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\IdealCustomerProfilesPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractIdealCustomerProfilesPerspectiveTransformer;

/**
 * Class IdealCustomerProfilesPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class IdealCustomerProfilesPerspectiveTransformer extends AbstractIdealCustomerProfilesPerspectiveTransformer
{

    /**
     * @param IdealCustomerProfilesPerspective $model
     *
     * @return array
     */
    public function transform(IdealCustomerProfilesPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('IdealCustomerProfilesPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('IdealCustomerProfilesPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
