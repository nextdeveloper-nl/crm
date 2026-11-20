<?php

namespace NextDeveloper\CRM\Http\Requests\AccountContacts;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

/**
 * Creates an Agenda contact and links it to a CRM account in one step.
 */
class AccountContactsCreateWithContactRequest extends AbstractFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'crm_account_id' => 'required|exists:crm_accounts,uuid|uuid',
            'role' => 'nullable|string',
            'is_primary' => 'nullable|boolean',
            'visibility' => 'nullable|string|in:private,team,specific',
            'data' => 'nullable|array',
            'contact' => 'required|array',
            'contact.name' => 'nullable|string',
            'contact.surname' => 'nullable|string',
            'contact.email' => 'nullable|string',
            'contact.home_phone' => 'nullable|string',
            'contact.cell_phone' => 'nullable|string',
            'contact.fax_number' => 'nullable|string',
            'contact.email_work' => 'nullable|string',
            'contact.website' => 'nullable|string',
            'contact.notes' => 'nullable|string',
            'contact.description' => 'nullable|string',
            // Optional: defaults to the first address book of the caller.
            'contact.agenda_address_book_id' => 'nullable|exists:agenda_address_books,uuid|uuid',
        ];
    }
}
