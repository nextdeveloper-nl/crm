<?php

namespace NextDeveloper\CRM\Http\Requests\AccountContacts;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class AccountContactsUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            // The account and the contact of a link are fixed; unlink and link again to change them.
        'role' => 'nullable|string',
        'is_primary' => 'nullable|boolean',
        'visibility' => 'nullable|string|in:private,team,specific',
        'data' => 'nullable|array',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}