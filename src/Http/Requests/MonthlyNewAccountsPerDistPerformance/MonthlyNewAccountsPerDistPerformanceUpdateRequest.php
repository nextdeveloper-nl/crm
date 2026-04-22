<?php

namespace NextDeveloper\CRM\Http\Requests\MonthlyNewAccountsPerDistPerformance;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class MonthlyNewAccountsPerDistPerformanceUpdateRequest extends AbstractFormRequest
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
        'distributor_id' => 'nullable|exists:distributors,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}