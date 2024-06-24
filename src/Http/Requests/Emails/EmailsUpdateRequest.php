<?php

namespace NextDeveloper\CRM\Http\Requests\Emails;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class EmailsUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'subject' => 'nullable|string',
        'content' => 'nullable|string',
        'email_meta' => 'nullable|string',
        'from' => 'nullable|string',
        'to' => 'nullable',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}