<?php

namespace NextDeveloper\CRM\Http\Requests\UserEmailsPerspective;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class UserEmailsPerspectiveUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'position' => 'nullable|string',
        'job' => 'nullable|string',
        'crm_tags' => 'nullable',
        'is_suspended' => 'nullable|boolean',
        'fullname' => 'nullable|string',
        'email' => 'nullable|string',
        'phone_number' => 'nullable|string',
        'communication_email_id' => 'nullable|exists:communication_emails,uuid|uuid',
        'from_email_address' => 'nullable|string',
        'subject' => 'nullable|string',
        'body' => 'nullable|string',
        'is_marketing_email' => 'nullable|boolean',
        'deliver_at' => 'nullable|date',
        'delivered_at' => 'nullable|date',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}