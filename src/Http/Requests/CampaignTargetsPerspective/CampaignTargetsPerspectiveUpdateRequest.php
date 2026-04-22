<?php

namespace NextDeveloper\CRM\Http\Requests\CampaignTargetsPerspective;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class CampaignTargetsPerspectiveUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'crm_campaign_id' => 'nullable|exists:crm_campaigns,uuid|uuid',
        'name' => 'nullable|string',
        'description' => 'nullable|string',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date',
        'status' => 'nullable|string',
        'target_name' => 'nullable|string',
        'target_description' => 'nullable|string',
        'responsible_account' => 'nullable|string',
        'responsible_name' => 'nullable|string',
        'target_user_count' => 'nullable|integer',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}