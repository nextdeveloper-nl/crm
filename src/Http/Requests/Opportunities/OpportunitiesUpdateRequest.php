<?php

namespace NextDeveloper\CRM\Http\Requests\Opportunities;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class OpportunitiesUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name'           => 'nullable|string|max:500',
        'description'    => 'nullable|string|max:500',
        'probability'    => 'integer',
        'stage'          => '',
        'source'         => 'nullable|string|max:500',
        'income'         => 'numeric',
        'deadline'       => 'nullable|date',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n
}