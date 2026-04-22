<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\CampaignTargetUsersPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractCampaignTargetUsersPerspectiveTransformer;

/**
 * Class CampaignTargetUsersPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class CampaignTargetUsersPerspectiveTransformer extends AbstractCampaignTargetUsersPerspectiveTransformer
{

    /**
     * @param CampaignTargetUsersPerspective $model
     *
     * @return array
     */
    public function transform(CampaignTargetUsersPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('CampaignTargetUsersPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('CampaignTargetUsersPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
