<?php

namespace NextDeveloper\CRM\Http\Requests\AccountsPerspective;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class AccountsPerspectiveCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string',
        'account_owners_fullname' => 'nullable|string',
        'account_owners_email' => 'nullable|string',
        'account_owners_phone_number' => 'nullable|string',
        'common_domain_id' => 'nullable|exists:common_domains,uuid|uuid',
        'domain_name' => 'nullable|string',
        'common_country_id' => 'nullable|exists:common_countries,uuid|uuid',
        'country_name' => 'nullable|string',
        'phone_number' => 'nullable|string',
        'description' => 'nullable|string',
        'iam_account_type_id' => 'nullable|exists:iam_account_types,uuid|uuid',
        'account_type' => 'nullable|string',
        'is_paying_customer' => 'nullable|boolean',
        'common_city_id' => 'nullable|exists:common_cities,uuid|uuid',
        'position' => 'nullable|string',
        'risk_level' => 'nullable|integer',
        'total_user_count' => 'nullable|integer',
        'registered_user_count' => 'nullable|integer',
        'is_sdr_qualified' => 'nullable|boolean',
        'is_sdr_qualification_required' => 'nullable|boolean',
        'disqualification_reason' => 'nullable|string',
        'office_phone_number' => 'nullable|string',
        'office_phone_extension' => 'nullable|string',
        'office_email' => 'nullable|string',
        'is_disabled' => 'nullable|boolean',
        'disabling_reason' => 'nullable|string',
        'is_suspended' => 'nullable|boolean',
        'suspension_reason' => 'nullable|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}