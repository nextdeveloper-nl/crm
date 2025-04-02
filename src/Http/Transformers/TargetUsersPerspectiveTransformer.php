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
    public function transform(TargetUsersPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('TargetUsersPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('TargetUsersPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
