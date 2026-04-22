<?php

namespace NextDeveloper\CRM\Http\Requests\CampaignTargetUsersPerspective;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class CampaignTargetUsersPerspectiveCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'campaign_name' => 'nullable|string',
        'campaign_status' => 'nullable|string',
        'crm_target_id' => 'nullable|exists:crm_targets,uuid|uuid',
        'target_name' => 'nullable|string',
        'crm_user_id' => 'nullable|exists:crm_users,uuid|uuid',
        'fullname' => 'nullable|string',
        'email' => 'nullable|string',
        'phone_number' => 'nullable|string',
        'account_name' => 'nullable|string',
        'responsible_account_id' => 'nullable|exists:responsible_accounts,uuid|uuid',
        'responsible_account' => 'nullable|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}