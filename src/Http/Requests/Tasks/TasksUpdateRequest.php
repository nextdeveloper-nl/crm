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
            'description' => 'nullable|string',
        'crm_account_id' => 'nullable|exists:crm_accounts,uuid|uuid',
        'priority' => 'integer',
        'is_finished' => 'boolean',
        'is_delayed' => 'boolean',
        'name' => 'nullable|string',
        'object_type' => 'nullable|string',
        'object_id' => 'nullable',
        'due_date' => 'nullable|date',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}