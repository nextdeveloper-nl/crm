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
            'crm_account_id' => 'nullable|exists:crm_accounts,uuid|uuid',
        'name' => 'nullable|string',
        'description' => 'nullable|string',
        'crm_target_id' => 'nullable|exists:crm_targets,uuid|uuid',
        'search_criteria' => 'nullable',
        'search_engine' => 'nullable',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}