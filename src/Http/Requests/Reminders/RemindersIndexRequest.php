<?php

namespace NextDeveloper\CRM\Http\Requests\Reminders;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

/**
 * Validates listing the reminders of one CRM record.
 */
class RemindersIndexRequest extends AbstractFormRequest
{
    /**
     * @return array
     */
    public function rules()
    {
        return [
            'object_type' => 'required|string',
            'object_id'   => 'required|uuid',
        ];
    }
}
