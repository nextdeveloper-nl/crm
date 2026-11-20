<?php

namespace NextDeveloper\CRM\Http\Requests\AccountContacts;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class AccountContactsCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'crm_account_id' => 'required|exists:crm_accounts,uuid|uuid',
        // The Agenda contact to link; it must be owned by the caller (checked in the service).
        'agenda_contact_id' => 'required|exists:agenda_contacts,uuid|uuid',
        'role' => 'nullable|string',
        'is_primary' => 'nullable|boolean',
        'visibility' => 'nullable|string|in:private,team,specific',
        'data' => 'nullable|array',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}