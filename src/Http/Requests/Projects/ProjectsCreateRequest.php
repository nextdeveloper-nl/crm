<?php

namespace NextDeveloper\CRM\Http\Requests\Projects;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class ProjectsCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
        'url' => 'required|string',
        'project_id' => 'nullable|string|exists:projects,uuid|uuid',
        'token' => 'nullable|string',
        'crm_account_id' => 'required|exists:crm_accounts,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}