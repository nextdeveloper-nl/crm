<?php

namespace NextDeveloper\CRM\Http\Requests\CrmOpportunity;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class CrmOpportunityUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules() {
        return [
            'name'           => 'nullable|string|max:500',
			'description'    => 'nullable|string|max:500',
			'probability'    => 'integer',
			'stage'          => '',
			'source'         => 'nullable|string|max:500',
			'income'         => 'integer',
			'deadline'       => 'nullable|date',
			'iam_account_id' => 'nullable|exists:iam_accounts,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n
}