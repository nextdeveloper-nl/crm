<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\IdealCustomerProfiles;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractIdealCustomerProfilesTransformer;

/**
 * Class IdealCustomerProfilesTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class IdealCustomerProfilesTransformer extends AbstractIdealCustomerProfilesTransformer
{

    /**
     * @param IdealCustomerProfiles $model
     *
     * @return array
     */
    public function transform(IdealCustomerProfiles $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('IdealCustomerProfiles', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('IdealCustomerProfiles', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
