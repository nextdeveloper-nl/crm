<?php

namespace NextDeveloper\CRM\Http\Requests\Users;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class UsersCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'position'            => 'nullable|string|max:150',
        'job'                 => 'nullable',
        'job_description'     => 'nullable|string|max:1000',
        'hobbies'             => 'nullable|string|max:500',
        'city'                => 'nullable|string|max:50',
        'email_risk'          => 'nullable',
        'relationship_status' => 'nullable',
        'is_evangelist'       => 'boolean',
        'martial_status'      => 'nullable',
        'education'           => 'nullable',
        'child_count'         => 'nullable|integer',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n\n\n
}