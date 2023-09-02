<?php

namespace NextDeveloper\CRM\Http\Requests\CrmAccount;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class CrmAccountCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules() {
        return [
            'is_paying_customer' => 'boolean',
			'risk_level'         => 'nullable|boolean',
			'city'               => 'nullable|string|max:50',
			'position'           => 'integer',
			'iam_account_id'     => 'required|exists:iam_accounts,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n
}