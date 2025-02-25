<?php

namespace NextDeveloper\CRM\Http\Requests\Users;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class UsersUpdateRequest extends AbstractFormRequest
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
        'risk' => 'nullable',
        'relationship_status' => 'nullable|string',
        'is_evangelist' => 'boolean',
        'is_single' => 'nullable|boolean',
        'education_level' => 'nullable',
        'child_count' => 'nullable|integer',
        'tags' => '',
        'is_suspended' => 'nullable|boolean',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}