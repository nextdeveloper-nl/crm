<?php

namespace NextDeveloper\CRM\Http\Requests\Projects;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class ProjectsUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string',
        'url' => 'nullable|string',
        'project_id' => 'nullable|string|exists:projects,uuid|uuid',
        'token' => 'nullable|string',
        'crm_account_id' => 'nullable|exists:crm_accounts,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}