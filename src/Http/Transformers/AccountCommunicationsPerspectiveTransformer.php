<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\AccountCommunicationsPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractAccountCommunicationsPerspectiveTransformer;

/**
 * Class AccountCommunicationsPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class AccountCommunicationsPerspectiveTransformer extends AbstractAccountCommunicationsPerspectiveTransformer
{

    /**
     * @param AccountCommunicationsPerspective $model
     *
     * @return array
     */
    public function transform(AccountCommunicationsPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('AccountCommunicationsPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('AccountCommunicationsPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
