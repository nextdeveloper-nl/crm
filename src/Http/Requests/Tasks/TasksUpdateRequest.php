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
        'start_date' => 'nullable|date',
        'progress' => 'integer|min:0|max:100',
        'crm_task_id' => 'nullable|exists:crm_tasks,uuid|uuid',
        'is_milestone' => 'boolean',
        'sort_order' => 'integer',
        'estimated_hours' => 'nullable|numeric|min:0',
        // Assignee (user uuid). Added below the generated rules: the flow task drawer reassigns a task through this.
        'iam_user_id' => 'nullable|uuid|exists:iam_users,uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}