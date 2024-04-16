<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\AccountsPerspectives;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractAccountsPerspectivesTransformer;

/**
 * Class AccountsPerspectivesTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class AccountsPerspectivesTransformer extends AbstractAccountsPerspectivesTransformer
{

    /**
     * @param AccountsPerspectives $model
     *
     * @return array
     */
    public function transform(AccountsPerspectives $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('AccountsPerspectives', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('AccountsPerspectives', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
