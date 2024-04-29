<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\Meetings;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractMeetingsTransformer;

/**
 * Class MeetingsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class MeetingsTransformer extends AbstractMeetingsTransformer
{

    /**
     * @param Meetings $model
     *
     * @return array
     */
    public function transform(Meetings $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('Meetings', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('Meetings', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
