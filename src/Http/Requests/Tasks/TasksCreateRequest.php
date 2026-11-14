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
            'description' => 'required|string',
        'crm_account_id' => 'nullable|exists:crm_accounts,uuid|uuid',
        'priority' => 'integer',
        'is_finished' => 'boolean',
        'is_delayed' => 'boolean',
        'name' => 'required|string',
        'object_type' => 'nullable|string',
        'object_id' => 'nullable',
        'due_date' => 'nullable|date',
        'start_date' => 'nullable|date',
        'progress' => 'integer|min:0|max:100',
        'crm_task_id' => 'nullable|exists:crm_tasks,uuid|uuid',
        'is_milestone' => 'boolean',
        'sort_order' => 'integer',
        'estimated_hours' => 'nullable|numeric|min:0',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}