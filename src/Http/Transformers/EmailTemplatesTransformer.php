<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\EmailTemplates;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractEmailTemplatesTransformer;

/**
 * Class EmailTemplatesTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class EmailTemplatesTransformer extends AbstractEmailTemplatesTransformer
{

    /**
     * @param EmailTemplates $model
     *
     * @return array
     */
    public function transform(EmailTemplates $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('EmailTemplates', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('EmailTemplates', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
