<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\Importers;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractImportersTransformer;

/**
 * Class ImportersTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class ImportersTransformer extends AbstractImportersTransformer
{

    /**
     * @param Importers $model
     *
     * @return array
     */
    public function transform(Importers $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('Importers', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('Importers', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
