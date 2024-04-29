<?php

namespace NextDeveloper\CRM\Http\Requests\Notes;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class NotesUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'crm_account_id' => 'nullable|exists:crm_accounts,uuid|uuid',
        'note' => 'nullable|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}