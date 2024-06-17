<?php

namespace NextDeveloper\CRM\Http\Requests\Meetings;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class MeetingsUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'agenda_calendar_item_id' => 'nullable|exists:agenda_calendar_items,uuid|uuid',
        'meeting_note' => 'nullable|string',
        'outcome' => 'nullable|string',
        'crm_account_id' => 'nullable|exists:crm_accounts,uuid|uuid',
        'customer_requirements' => 'nullable|string',
        'suggestions' => 'nullable|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}