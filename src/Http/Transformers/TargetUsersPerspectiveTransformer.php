<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\TargetUsersPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractTargetUsersPerspectiveTransformer;

/**
 * Class TargetUsersPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class TargetUsersPerspectiveTransformer extends AbstractTargetUsersPerspectiveTransformer
{

    /**
     * @param TargetUsersPerspective $model
     *
     * @return array
     */
    /**
     * The perspective's own `iam_account_id` is the account that owns the target list,
     * not the account the contact belongs to, so the customer list had no company to
     * show. `account_name` / `account_uuid` come from the view (see leo4
     * database/scripts/target_users_perspective_account.sql) and are appended here.
     *
     * The cache variant carries the payload shape: keyed on the uuid alone, payloads
     * cached before these fields existed would keep hiding them until something evicted
     * them, and nothing evicts a perspective row that never changes.
     */
    public function transform(TargetUsersPerspective $model)
    {
        $key = CacheHelper::getKey('TargetUsersPerspective', $model->uuid, 'TransformedWithAccount');

        $transformed = Cache::get($key);

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        $transformed['account_name'] = $model->account_name;
        $transformed['account_uuid'] = $model->account_uuid;

        Cache::set($key, $transformed);

        return $transformed;
    }
}
