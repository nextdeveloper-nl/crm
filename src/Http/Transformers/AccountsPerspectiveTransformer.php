<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\AccountsPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractAccountsPerspectiveTransformer;
use NextDeveloper\CRM\Helpers\TenantHelper;
use NextDeveloper\IAM\Database\Models\Accounts;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * Class AccountsPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class AccountsPerspectiveTransformer extends AbstractAccountsPerspectiveTransformer
{

    /**
     * @param AccountsPerspective $model
     *
     * @return array
     */
    public function transform(AccountsPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('AccountsPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $this->hideOwnerDetailsOfForeignCustomer($model, $transformed);
        }

        $transformed = parent::transform($model);

        if($transformed['iam_account_id'] == null) {
            $account = Accounts::withoutGlobalScopes()
                ->where('id', $model->iam_account_id)
                ->first();

            $transformed['iam_account_id'] = $account->uuid;
        }

        Cache::set(
            CacheHelper::getKey('AccountsPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $this->hideOwnerDetailsOfForeignCustomer($model, $transformed);
    }

    /**
     * Multi-tenancy: a tenant that only attached itself to a company that already existed (is_created_by_owner = false)
     * must not read that account's owner name, email and phone. Applied AFTER the cache read: the cached payload is
     * shared by every viewer, the masking depends on the viewer.
     */
    private function hideOwnerDetailsOfForeignCustomer(AccountsPerspective $model, array $transformed): array
    {
        if (!TenantHelper::isolationEnabled()
            || $model->is_created_by_owner !== false
            || TenantHelper::isPlatformAccount((int) UserHelper::currentAccount()->id)) {
            return $transformed;
        }

        foreach (['account_owners_fullname', 'account_owners_email', 'account_owners_phone_number'] as $field) {
            if (array_key_exists($field, $transformed)) {
                $transformed[$field] = null;
            }
        }

        return $transformed;
    }
}
