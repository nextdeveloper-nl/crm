<?php

namespace NextDeveloper\CRM\Http\Requests\Reminders;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

/**
 * Validates a new reminder on a CRM record. object_id is the record's uuid; object_type is
 * checked against the CRM models in RemindersService.
 */
class RemindersCreateRequest extends AbstractFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'object_type'     => 'required|string',
            'object_id'       => 'required|uuid',
            'remind_datetime' => 'required|date|after:now',
            'note'            => 'nullable|string',
            'iam_user_id'     => 'nullable|uuid|exists:iam_users,uuid',
        ];
    }
}
