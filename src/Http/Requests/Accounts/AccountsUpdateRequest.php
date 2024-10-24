<?php

namespace NextDeveloper\CRM\Http\Requests\Accounts;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class AccountsUpdateRequest extends AbstractFormRequest
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
        'company_size' => 'integer',
        'sector_focus' => 'nullable',
        'industry' => 'nullable',
        'is_startup' => 'nullable|boolean',
        'regulatory_and_compliance' => 'nullable',
        'employee_count' => 'nullable|integer',
        'office_cities' => 'nullable',
        'headquarter_city' => 'nullable|string',
        'production_people_count' => 'integer',
        'sales_people_count' => 'integer',
        'marketing_people_count' => 'integer',
        'support_people_count' => 'integer',
        'automation_count' => 'integer',
        'additional_information' => 'nullable|string',
        'target_markets' => 'nullable',
        'partners_with' => 'nullable',
        'services' => 'nullable',
        'is_suspended' => 'boolean',
        'is_service_enabled' => 'boolean',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}