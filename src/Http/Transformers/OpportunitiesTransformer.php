<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\Opportunities;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractOpportunitiesTransformer;

/**
 * Class OpportunitiesTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class OpportunitiesTransformer extends AbstractOpportunitiesTransformer
{

    /**
     * @param Opportunities $model
     *
     * @return array
     */
    public function transform(Opportunities $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('Opportunities', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('Opportunities', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
