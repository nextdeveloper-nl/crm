<?php

namespace NextDeveloper\CRM\Http\Requests\Tasks;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class TasksCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
        'description' => 'required|string',
        'crm_account_id' => 'required|exists:crm_accounts,uuid|uuid',
        'priority' => 'integer',
        'is_finished' => 'boolean',
        'is_delayed' => 'boolean',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}