<?php

namespace NextDeveloper\CRM\Http\Requests\Tasks;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class TasksUpdateRequest extends AbstractFormRequest
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
        'priority' => 'integer',
        'is_finished' => 'boolean',
        'is_delayed' => 'boolean',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}