<?php

namespace NextDeveloper\CRM\Http\Requests\SalesPeoplePerspective;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class SalesPeoplePerspectiveCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string',
        'surname' => 'nullable|string',
        'fullname' => 'nullable|string',
        'email' => 'nullable|string',
        'about' => 'nullable|string',
        'pronoun' => 'nullable|string',
        'birthday' => 'nullable|date',
        'nin' => 'nullable|string',
        'common_country_id' => 'nullable|exists:common_countries,uuid|uuid',
        'country' => 'nullable|string',
        'common_language_id' => 'nullable|exists:common_languages,uuid|uuid',
        'language' => 'nullable|string',
        'iam_updated_at' => 'nullable|date',
        'phone_number' => 'nullable|string',
        'is_registered' => 'nullable|boolean',
        'is_nin_verified' => 'nullable|boolean',
        'is_email_verified' => 'nullable|boolean',
        'is_phone_number_verified' => 'nullable|boolean',
        'profile_picture_identity' => 'nullable|integer',
        'position' => 'nullable|string',
        'job' => 'nullable|string',
        'job_description' => 'nullable|string',
        'hobbies' => 'nullable|string',
        'city' => 'nullable|string',
        'email_risk' => 'nullable',
        'relationship_status' => 'nullable|string',
        'is_evangelist' => 'nullable|boolean',
        'is_single' => 'nullable|boolean',
        'education' => 'nullable',
        'child_count' => 'nullable|integer',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}