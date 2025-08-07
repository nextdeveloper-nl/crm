<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\AccountsSummarizedPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractAccountsSummarizedPerspectiveTransformer;

/**
 * Class AccountsSummarizedPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class AccountsSummarizedPerspectiveTransformer extends AbstractAccountsSummarizedPerspectiveTransformer
{

    /**
     * @param AccountsSummarizedPerspective $model
     *
     * @return array
     */
    public function transform(AccountsSummarizedPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('AccountsSummarizedPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('AccountsSummarizedPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
