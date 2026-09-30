<?php

namespace NextDeveloper\CRM\Http\Requests\Reminders;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

/**
 * Validates snoozing a CRM reminder: the new time must be in the future.
 */
class RemindersSnoozeRequest extends AbstractFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'snooze_datetime' => 'required|date|after:now',
        ];
    }
}
