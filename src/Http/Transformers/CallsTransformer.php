<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\Calls;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractCallsTransformer;

/**
 * Class CallsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class CallsTransformer extends AbstractCallsTransformer
{

    /**
     * @param Calls $model
     *
     * @return array
     */
    public function transform(Calls $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('Calls', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('Calls', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
