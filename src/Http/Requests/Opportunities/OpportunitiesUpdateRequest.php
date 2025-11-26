<?php

namespace NextDeveloper\CRM\Http\Requests\Opportunities;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class OpportunitiesUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string',
        'description' => 'nullable|string',
        'probability' => 'integer',
        'opportunity_stage' => '',
        'source' => 'nullable|string',
        'income' => '',
        'deadline' => 'nullable|date',
        'crm_account_id' => 'nullable|exists:crm_accounts,uuid|uuid',
        'tags' => '',
        'common_currency_id' => 'nullable|exists:common_currencies,uuid|uuid',
        'type' => 'string',
        'opportunity_type' => 'nullable|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}