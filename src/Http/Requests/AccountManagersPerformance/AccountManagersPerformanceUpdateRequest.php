<?php

namespace NextDeveloper\CRM\Http\Requests\AccountManagersPerformance;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class AccountManagersPerformanceUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'creation_date' => 'nullable|date',
        'account_manager' => 'nullable|string',
        'total_accounts' => 'nullable|integer',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}