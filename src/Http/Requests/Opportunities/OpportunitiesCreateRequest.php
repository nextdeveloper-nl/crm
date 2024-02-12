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
            'name' => 'nullable|string',
        'description' => 'nullable|string',
        'probability' => 'integer',
        'stage' => '',
        'source' => 'nullable|string',
        'income' => 'required',
        'deadline' => 'nullable|date',
        'crm_account_id' => 'nullable|exists:crm_accounts,uuid|uuid',
        'tags' => '',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n\n\n
}