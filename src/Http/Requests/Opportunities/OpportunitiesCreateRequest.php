<?php

namespace NextDeveloper\CRM\Http\Requests\Opportunities;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class OpportunitiesCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
        'description' => 'nullable|string',
        'probability' => 'integer|min:0|max:100',
        'source' => 'nullable|string',
        'income' => 'nullable|numeric|min:0',
        'deadline' => 'nullable|date',
        'crm_account_id' => 'required|exists:crm_accounts,uuid|uuid',
        'tags' => '',
        'opportunity_stage' => 'nullable|string',
        'type' => 'string',
        'reason_lost' => 'nullable|string',
        'common_currency_id' => 'nullable|exists:common_currencies,uuid|uuid',
        'crm_campaign_id' => 'nullable|exists:crm_campaigns,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE


}