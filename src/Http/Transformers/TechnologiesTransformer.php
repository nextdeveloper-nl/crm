<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\Technologies;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractTechnologiesTransformer;

/**
 * Class TechnologiesTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class TechnologiesTransformer extends AbstractTechnologiesTransformer
{

    /**
     * @param Technologies $model
     *
     * @return array
     */
    public function transform(Technologies $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('Technologies', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('Technologies', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
