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
            'probability' => 'integer',
            'source' => 'nullable|string',
            'income' => '',
            'deadline' => 'nullable|date',
            'crm_account_id' => 'required|exists:crm_accounts,uuid|uuid',
            'tags' => '',
            'opportunity_stage' => '',
            'type' => 'string',
            'reason_lost' => 'nullable|string',
            'common_currency_id' => 'exists:currencies,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
