<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\Industries;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractIndustriesTransformer;

/**
 * Class IndustriesTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class IndustriesTransformer extends AbstractIndustriesTransformer
{

    /**
     * @param Industries $model
     *
     * @return array
     */
    public function transform(Industries $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('Industries', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('Industries', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
