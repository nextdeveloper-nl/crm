<?php

namespace NextDeveloper\CRM\Http\Requests\DailyNewAccountsPerformance;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class DailyNewAccountsPerformanceCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'day_start' => 'nullable|date',
        'day_code' => 'nullable|string',
        'count' => 'nullable|integer',
        'count_leadocean' => 'nullable|integer',
        'count_without_leadocean' => 'nullable|integer',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}
