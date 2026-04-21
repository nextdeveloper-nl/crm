<?php

namespace NextDeveloper\CRM\Http\Requests\TargetUsersPerspective;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class TargetUsersPerspectiveCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'pronoun' => 'nullable|string',
        'name' => 'nullable|string',
        'surname' => 'nullable|string',
        'fullname' => 'nullable|string',
        'birthday' => 'nullable|date',
        'email' => 'nullable|string',
        'phone_number' => 'nullable|string',
        'common_country_id' => 'nullable|exists:common_countries,uuid|uuid',
        'common_language_id' => 'nullable|exists:common_languages,uuid|uuid',
        'user_tags' => 'nullable',
        'about' => 'nullable|string',
        'crm_user_id' => 'nullable|exists:crm_users,uuid|uuid',
        'position' => 'nullable|string',
        'job' => 'nullable|string',
        'job_description' => 'nullable|string',
        'hobbies' => 'nullable|string',
        'city' => 'nullable|string',
        'relationship_status' => 'nullable|string',
        'is_evangelist' => 'nullable|boolean',
        'is_single' => 'nullable|boolean',
        'education_level' => 'nullable',
        'child_count' => 'nullable|integer',
        'crm_tags' => 'nullable',
        'is_suspended' => 'nullable|boolean',
        'crm_target_id' => 'nullable|exists:crm_targets,uuid|uuid',
        'target_name' => 'nullable|string',
        'target_description' => 'nullable|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}