<?php

namespace NextDeveloper\CRM\Http\Requests\CampaignTargets;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class CampaignTargetsUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'crm_target_id' => 'nullable|exists:crm_targets,uuid|uuid',
        'crm_campaign_id' => 'nullable|exists:crm_campaigns,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}