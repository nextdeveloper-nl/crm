<?php

namespace NextDeveloper\CRM\Http\Requests\Emails;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class EmailsCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'subject' => 'required|string',
        'content' => 'required|string',
        'iam_account_it' => 'required|integer',
        'crm_account_id' => 'required|exists:crm_accounts,uuid|uuid',
        'email_meta' => 'nullable|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}