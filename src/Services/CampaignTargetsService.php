<?php

namespace NextDeveloper\CRM\Services;

use NextDeveloper\CRM\Database\Models\CampaignTargets;
use NextDeveloper\CRM\Database\Models\Campaigns;
use NextDeveloper\CRM\Database\Models\Targets;
use NextDeveloper\Events\Services\Events;
use NextDeveloper\CRM\Helpers\TenantHelper;
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
        // Multi-tenancy: both the target and the campaign must belong to the current
        // account. Previously both were resolved by UUID with AuthorizationScope
        // removed, so a tenant could link another tenant's target (contact list)
        // to its own campaign and read the contacts. Missing or foreign records
        // both result in a 404.
        $target = TenantHelper::findOwnedOrFail(Targets::class, $data['crm_target_id'] ?? null);

        $campaign = TenantHelper::findOwnedOrFail(Campaigns::class, $data['crm_campaign_id'] ?? null);

        $model = new CampaignTargets();
        $model->crm_target_id   = $target->id;
        $model->crm_campaign_id = $campaign->id;
        $model->save();

        Events::fire('created:NextDeveloper\CRM\CampaignTargets', $model);

        return $model;
    }
}
