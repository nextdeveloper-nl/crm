<?php

namespace NextDeveloper\CRM\Http\Requests\Notes;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class NotesCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'crm_account_id' => 'required|exists:crm_accounts,uuid|uuid',
        'note' => 'required|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}