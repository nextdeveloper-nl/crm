<?php

namespace NextDeveloper\CRM\Http\Requests\AccountCommunicationsPerspective;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class AccountCommunicationsPerspectiveCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'crm_account_id' => 'nullable|exists:crm_accounts,uuid|uuid',
        'direction' => 'nullable|integer',
        'from_email_address' => 'nullable|string',
        'recipient' => 'nullable|string',
        'subject' => 'nullable|string',
        'body' => 'nullable|string',
        'content_type' => 'nullable|string',
        'is_marketing_email' => 'nullable|boolean',
        'message_status' => 'nullable|string',
        'deliver_at' => 'nullable|date',
        'delivered_at' => 'nullable|date',
        'read_at' => 'nullable|date',
        'failed_at' => 'nullable|date',
        'failure_reason' => 'nullable|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}
