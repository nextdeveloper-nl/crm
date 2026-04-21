<?php

namespace NextDeveloper\CRM\Http\Requests\AccountManagersPerspective;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class AccountManagersPerspectiveCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string',
        'account_manager' => 'nullable|string',
        'is_paying_customer' => 'nullable|boolean',
        'tags' => 'nullable',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}