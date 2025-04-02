<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\SectorFocus;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractSectorFocusTransformer;

/**
 * Class SectorFocusTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class SectorFocusTransformer extends AbstractSectorFocusTransformer
{

    /**
     * @param SectorFocus $model
     *
     * @return array
     */
    public function transform(SectorFocus $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('SectorFocus', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('SectorFocus', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
