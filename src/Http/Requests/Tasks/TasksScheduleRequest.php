<?php

namespace NextDeveloper\CRM\Http\Requests\Tasks;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

/**
 * POST /crm/tasks/schedule — dates and progress of several tasks at once (leo4#1245).
 */
class TasksScheduleRequest extends AbstractFormRequest
{
    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'tasks' => 'required|array|min:1|max:500',
            'tasks.*.id' => 'required|uuid',
            'tasks.*.start_date' => 'nullable|date',
            'tasks.*.due_date' => 'nullable|date',
            'tasks.*.progress' => 'nullable|integer|min:0|max:100',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tasks.required' => 'Send at least one task to schedule.',
            'tasks.max' => 'Schedule at most 500 tasks at once.',
            'tasks.*.progress.max' => 'Progress goes from 0 to 100.',
        ];
    }
}
