<?php

namespace NextDeveloper\CRM\Http\Requests\CrmAccount;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class CrmAccountUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules() {
        return [
            'iam_account_id'     => 'nullable|exists:iam_accounts,uuid|uuid',
			'is_paying_customer' => 'boolean',
			'risk_level'         => 'nullable|boolean',
			'city'               => 'nullable|string|max:50',
			'position'           => 'integer',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n
}