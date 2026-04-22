<?php

namespace NextDeveloper\CRM\Http\Requests\MonthlyPayingCustomersPerformance;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class MonthlyPayingCustomersPerformanceUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'month_start' => 'nullable|date',
        'month_end' => 'nullable|date',
        'month_name' => 'nullable|string',
        'month_code' => 'nullable|string',
        'count' => 'nullable|integer',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}