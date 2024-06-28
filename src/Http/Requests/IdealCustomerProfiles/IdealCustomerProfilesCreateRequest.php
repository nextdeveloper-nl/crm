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
        'customer_positions' => 'nullable|string',
        'company_size' => 'nullable|string',
        'is_working_home_office' => 'boolean',
        'pain_points' => 'nullable',
        'solutions_interested_in' => 'nullable|string',
        'current_technology_stack' => 'nullable|string',
        'budget' => 'nullable',
        'decision_making_process' => 'nullable|string',
        'implementation_timeline' => 'nullable|string',
        'unique_selling_proposition' => 'nullable|string',
        'lead_generation_channels' => 'nullable|string',
        'sales_process' => 'nullable|string',
        'sales_funnel' => 'nullable|string',
        'additional_notes' => 'nullable|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}