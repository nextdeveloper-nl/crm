<?php

namespace NextDeveloper\CRM\Http\Requests\Emails;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class EmailsCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'subject' => 'required|string',
        'content' => 'required|string',
        'email_meta' => 'nullable|string',
        'from' => 'nullable|string',
        'to' => 'nullable',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}