<?php

namespace NextDeveloper\CRM\Http\Requests\Emails;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class EmailsUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'subject' => 'nullable|string',
        'content' => 'nullable|string',
        'iam_account_it' => 'nullable|integer',
        'crm_account_id' => 'nullable|exists:crm_accounts,uuid|uuid',
        'email_meta' => 'nullable|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}