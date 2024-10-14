<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\Projects;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractProjectsTransformer;

/**
 * Class ProjectsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class ProjectsTransformer extends AbstractProjectsTransformer
{

    /**
     * @param Projects $model
     *
     * @return array
     */
    public function transform(Projects $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('Projects', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('Projects', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
