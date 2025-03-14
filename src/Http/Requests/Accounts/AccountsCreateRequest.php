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
        'tags' => '',
        'additional_information' => 'nullable|string',
        'is_suspended' => 'boolean',
        'is_service_enabled' => 'boolean',
        'is_disabled' => 'boolean',
        'disabling_reason' => 'nullable|string',
        'suspension_reason' => 'nullable|string',
        'technology_rank' => 'nullable|integer',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}