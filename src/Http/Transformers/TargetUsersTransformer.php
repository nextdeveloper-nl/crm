<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\Targets;
use NextDeveloper\CRM\Database\Models\TargetUsers;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Database\Models\Users;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractTargetUsersTransformer;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Class TargetUsersTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class TargetUsersTransformer extends AbstractTargetUsersTransformer
{

    /**
     * @param TargetUsers $model
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public function transform(TargetUsers $model)
    {

        $crmTargetId = Targets::where('id', $model->crm_target_id)->first()?->uuid;
        $crmUserId = Users::where('id', $model->crm_user_id)->first()?->uuid;


        $cacheKey = $crmUserId . '_' . $crmTargetId;

        $transformed = Cache::get(
            CacheHelper::getKey('TargetUsers', $cacheKey, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = $this->buildPayload(
            [
                'crm_target_id'  =>  $crmTargetId,
                'crm_user_id'  =>  $crmUserId,
                'created_at'  =>  $model->created_at,
                'updated_at'  =>  $model->updated_at,
            ]
        );

        Cache::set(
            CacheHelper::getKey('TargetUsers', $cacheKey, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
