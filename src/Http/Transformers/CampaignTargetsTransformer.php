<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\CampaignTargets;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractCampaignTargetsTransformer;

/**
 * Class CampaignTargetsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class CampaignTargetsTransformer extends AbstractCampaignTargetsTransformer
{

    /**
     * @param CampaignTargets $model
     *
     * @return array
     */
    public function transform(CampaignTargets $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('CampaignTargets', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('CampaignTargets', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
