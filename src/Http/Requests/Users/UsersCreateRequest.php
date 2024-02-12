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
            'position' => 'nullable|string',
        'job' => 'nullable|string',
        'job_description' => 'nullable|string',
        'hobbies' => 'nullable|string',
        'city' => 'nullable|string',
        'email_risk' => 'nullable',
        'relationship_status' => 'nullable|string',
        'is_evangelist' => 'boolean',
        'is_single' => 'nullable|boolean',
        'education' => 'nullable',
        'child_count' => 'nullable|integer',
        'tags' => '',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n\n\n
}