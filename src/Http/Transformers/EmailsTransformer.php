<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\Emails;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractEmailsTransformer;

/**
 * Class EmailsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class EmailsTransformer extends AbstractEmailsTransformer
{

    /**
     * @param Emails $model
     *
     * @return array
     */
    public function transform(Emails $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('Emails', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('Emails', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
