<?php

namespace NextDeveloper\CRM\Http\Requests\Tasks;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

/**
 * POST /crm/tasks/dependencies — the successor waits for the predecessor (leo4#1245).
 */
class TasksDependencyRequest extends AbstractFormRequest
{
    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'predecessor_id' => 'required|uuid',
            'successor_id' => 'required|uuid|different:predecessor_id',
            'type' => 'nullable|in:finish_to_start,start_to_start,finish_to_finish,start_to_finish',
            'lag_days' => 'nullable|integer|min:-365|max:365',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'predecessor_id.required' => 'Choose the task that comes first.',
            'successor_id.required' => 'Choose the task that waits.',
            'successor_id.different' => 'A task can not depend on itself.',
        ];
    }
}
