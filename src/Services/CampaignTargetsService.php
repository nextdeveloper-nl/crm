<?php

namespace NextDeveloper\CRM\Services;

use NextDeveloper\CRM\Database\Models\CampaignTargets;
use NextDeveloper\CRM\Database\Models\Campaigns;
use NextDeveloper\CRM\Database\Models\Targets;
use NextDeveloper\Events\Services\Events;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
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
        $target = Targets::withoutGlobalScope(AuthorizationScope::class)
            ->where('uuid', $data['crm_target_id'])
            ->firstOrFail();

        $campaign = Campaigns::withoutGlobalScope(AuthorizationScope::class)
            ->where('uuid', $data['crm_campaign_id'])
            ->firstOrFail();

        $model = new CampaignTargets();
        $model->crm_target_id   = $target->id;
        $model->crm_campaign_id = $campaign->id;
        $model->save();

        Events::fire('created:NextDeveloper\CRM\CampaignTargets', $model);

        return $model;
    }
}
