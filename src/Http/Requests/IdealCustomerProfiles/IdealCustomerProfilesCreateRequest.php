<?php

namespace NextDeveloper\CRM\Http\Requests\IdealCustomerProfiles;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class IdealCustomerProfilesCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'crm_account_id' => 'required|exists:crm_accounts,uuid|uuid',
        'company_size' => 'nullable|string',
        'is_working_home_office' => 'boolean',
        'current_technology_stack' => 'nullable',
        'additional_notes' => 'nullable|string',
        'growth_stage' => 'nullable|string',
        'geographical_focus' => 'nullable',
        'business_model' => 'nullable|string',
        'verticals' => 'nullable',
        'technology_rank' => 'nullable|integer',
        'keywords' => 'nullable',
        'name' => 'required|string',
        'description' => 'nullable|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}