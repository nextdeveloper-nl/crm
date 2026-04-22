<?php

namespace NextDeveloper\CRM\Services;

use NextDeveloper\CRM\Database\Models\CampaignTargets;
use NextDeveloper\Events\Services\Events;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCampaignTargetsService;

/**
 * This class is responsible from managing the data for CampaignTargets
 *
 * Class CampaignTargetsService.
 *
 * @package NextDeveloper\CRM\Database\Models
 */
class CampaignTargetsService extends AbstractCampaignTargetsService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
    public static function create(array $data)
    {
        $model = new CampaignTargets();
        $model->crm_target_id   = $data['crm_target_id'];
        $model->crm_campaign_id = $data['crm_campaign_id'];
        $model->save();

        Events::fire('created:NextDeveloper\CRM\CampaignTargets', $model);

        return $model->fresh();
    }
}
