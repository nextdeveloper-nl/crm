<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\OpportunitiesPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractOpportunitiesPerspectiveTransformer;

/**
 * Class OpportunitiesPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class OpportunitiesPerspectiveTransformer extends AbstractOpportunitiesPerspectiveTransformer
{

    /**
     * @param OpportunitiesPerspective $model
     *
     * @return array
     */
    public function transform(OpportunitiesPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('OpportunitiesPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('OpportunitiesPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
