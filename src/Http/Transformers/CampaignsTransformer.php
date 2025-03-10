<?php

namespace NextDeveloper\CRM\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\CRM\Database\Models\Campaigns;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\CRM\Http\Transformers\AbstractTransformers\AbstractCampaignsTransformer;

/**
 * Class CampaignsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\CRM\Http\Transformers
 */
class CampaignsTransformer extends AbstractCampaignsTransformer
{

    /**
     * @param Campaigns $model
     *
     * @return array
     */
    public function transform(Campaigns $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('Campaigns', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('Campaigns', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
