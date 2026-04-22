<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\CampaignTargetsPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractCampaignTargetsPerspectiveTransformer;

/**
 * Class CampaignTargetsPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class CampaignTargetsPerspectiveTransformer extends AbstractCampaignTargetsPerspectiveTransformer
{

    /**
     * @param CampaignTargetsPerspective $model
     *
     * @return array
     */
    public function transform(CampaignTargetsPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('CampaignTargetsPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('CampaignTargetsPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
