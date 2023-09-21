<?php

namespace NextDeveloper\CRM\Http\Requests\UserManagers;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class UserManagersCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'crm_user_id' => 'required|exists:crm_users,uuid|uuid',
        'iam_user_id' => 'required|exists:iam_users,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n
}