<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\Notes;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractNotesTransformer;

/**
 * Class NotesTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class NotesTransformer extends AbstractNotesTransformer
{

    /**
     * @param Notes $model
     *
     * @return array
     */
    public function transform(Notes $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('Notes', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('Notes', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
