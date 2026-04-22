<?php

namespace NextDeveloper\CRM\Http\Requests\OpportunitiesPerspective;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class OpportunitiesPerspectiveCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string',
        'description' => 'nullable|string',
        'probability' => 'nullable|integer',
        'opportunity_stage' => 'nullable',
        'source' => 'nullable|string',
        'income' => 'nullable',
        'deadline' => 'nullable|date',
        'account_name' => 'nullable|string',
        'crm_account_id' => 'nullable|exists:crm_accounts,uuid|uuid',
        'responsible_account' => 'nullable|string',
        'responsible_name' => 'nullable|string',
        'quote_count' => 'nullable|integer',
        'meeting_count' => 'nullable|integer',
        'call_count' => 'nullable|integer',
        'project_count' => 'nullable|integer',
        'type' => 'nullable|string',
        'tags' => 'nullable',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}