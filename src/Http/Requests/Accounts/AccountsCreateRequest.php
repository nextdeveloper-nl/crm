<?php

namespace NextDeveloper\CRM\Http\Requests\Accounts;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class AccountsCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'is_paying_customer' => 'boolean',
        'risk_level' => 'nullable|integer',
        'common_city_id' => 'nullable|exists:common_cities,uuid|uuid',
        'position' => 'nullable|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n\n\n
}