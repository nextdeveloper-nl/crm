<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\AccountsPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractAccountsPerspectiveTransformer;
use NextDeveloper\IAM\Database\Models\Accounts;

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
            return $transformed;
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

        return $transformed;
    }
}
