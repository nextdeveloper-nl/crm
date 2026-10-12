<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\ImporterRuns;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractImporterRunsTransformer;

/**
 * Class ImporterRunsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class ImporterRunsTransformer extends AbstractImporterRunsTransformer
{

    /**
     * @param ImporterRuns $model
     *
     * @return array
     */
    public function transform(ImporterRuns $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('ImporterRuns', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('ImporterRuns', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
