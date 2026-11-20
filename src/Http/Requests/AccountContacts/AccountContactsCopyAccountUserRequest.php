<?php

namespace NextDeveloper\CRM\Http\Requests\AccountContacts;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

/**
 * Copies an account user (crm_users uuid, as listed by the people endpoint) to the caller's own contacts.
 */
class AccountContactsCopyAccountUserRequest extends AbstractFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'crm_user_id' => 'required|exists:crm_users,uuid|uuid',
        ];
    }
}
