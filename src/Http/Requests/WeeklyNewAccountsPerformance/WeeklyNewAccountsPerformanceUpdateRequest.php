<?php

namespace NextDeveloper\CRM\Http\Requests\WeeklyNewAccountsPerformance;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class WeeklyNewAccountsPerformanceUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'week_start' => 'nullable|date',
        'week_end' => 'nullable|date',
        'week_number' => 'nullable|string',
        'count' => 'nullable|integer',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}