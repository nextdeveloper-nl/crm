<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\SalesPeoplePerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractSalesPeoplePerspectiveTransformer;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * Class SalesPeoplePerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class SalesPeoplePerspectiveTransformer extends AbstractSalesPeoplePerspectiveTransformer
{

    /**
     * @param SalesPeoplePerspective $model
     *
     * @return array
     */
    public function transform(SalesPeoplePerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('SalesPeoplePerspective', $model->uuid, 'Transformed')
        );

        $transformed = parent::transform($model);

        // Get a profile picture url
        $transformed['profile_picture_url'] = UserHelper::getUsersProfilePictureUrl(
            $transformed['email'],
            $transformed['profile_picture_identity'] ?? null
        );

        Cache::set(
            CacheHelper::getKey('SalesPeoplePerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
