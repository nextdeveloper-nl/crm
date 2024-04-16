<?php

namespace NextDeveloper\CRM\Http\Requests\AccountManagers;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class AccountManagersCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'crm_account_id' => 'required|exists:crm_accounts,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n\n\n
}