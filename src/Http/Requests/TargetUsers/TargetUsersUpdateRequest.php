<?php

namespace NextDeveloper\CRM\Http\Requests\TargetUsers;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class TargetUsersUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'crm_target_id' => 'nullable|exists:crm_targets,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}