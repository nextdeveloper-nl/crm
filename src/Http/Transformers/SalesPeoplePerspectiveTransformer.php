<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Authorization\Roles\SalesAdminRole;
use NextDeveloper\CRM\Authorization\Roles\SalesManagerRole;
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

        // The team list is visible to every sales role of the account, but national id, birthday and phone number
        // are personal data: only the person themself, a sales manager or a sales admin gets them. This runs after
        // the cache write on purpose, so a caller specific (redacted) copy never ends up in the shared cache.
        if (!self::canSeePersonalData($model)) {
            foreach (self::PERSONAL_FIELDS as $field) {
                if (array_key_exists($field, $transformed)) {
                    $transformed[$field] = null;
                }
            }
        }

        return $transformed;
    }

    /**
     * Fields hidden from callers that are not allowed to see a colleague's personal data.
     */
    private const PERSONAL_FIELDS = ['nin', 'birthday', 'phone_number'];

    /**
     * The row's own person, sales managers and sales admins see the personal fields; other sales roles do not.
     */
    private static function canSeePersonalData(SalesPeoplePerspective $model): bool
    {
        return $model->iam_user_id == UserHelper::me()->id
            || UserHelper::has(SalesManagerRole::NAME)
            || UserHelper::has(SalesAdminRole::NAME);
    }
}
