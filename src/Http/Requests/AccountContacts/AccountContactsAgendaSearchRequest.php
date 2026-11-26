<?php

namespace NextDeveloper\CRM\Http\Requests\AccountContacts;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

/**
 * Searches the caller's own Agenda contacts (to link one of them to a CRM account).
 */
class AccountContactsAgendaSearchRequest extends AbstractFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string|max:100',
            'rowCount' => 'nullable|integer|min:1|max:50',
        ];
    }
}
