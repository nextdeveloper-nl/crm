<?php

namespace NextDeveloper\CRM\Http\Requests\Calls;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class CallsUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'nullable|string',
        'description' => 'nullable|string',
        'iam_account_it' => 'nullable|integer',
        'crm_account_id' => 'nullable|exists:crm_accounts,uuid|uuid',
        'disposition' => 'nullable|string',
        'duration' => 'nullable|integer',
        'from_number' => 'nullable|string',
        'to_number' => 'nullable|string',
        'call_direction' => 'nullable|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}