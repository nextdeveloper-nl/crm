<?php

namespace NextDeveloper\CRM\Http\Requests\Targets;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class TargetsCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
        'description' => 'nullable|string',
        'list_user_count' => 'nullable|integer',
        'type' => 'string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}