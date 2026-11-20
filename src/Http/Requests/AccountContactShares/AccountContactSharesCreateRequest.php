<?php

namespace NextDeveloper\CRM\Http\Requests\AccountContactShares;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class AccountContactSharesCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'crm_account_contact_id' => 'required|exists:crm_account_contacts,uuid|uuid',
        'shared_iam_user_id' => 'required|exists:iam_users,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}