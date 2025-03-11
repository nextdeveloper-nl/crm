<?php

namespace NextDeveloper\CRM\Http\Requests\Campaigns;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class CampaignsCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
        'description' => 'nullable|string',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date',
        'status' => 'required|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}