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
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date',
        'status' => 'nullable|string',
        'campaign_type' => 'nullable|string|in:sales,marketing',
        'flow_pipeline_id' => 'nullable|exists:flow_pipelines,uuid|uuid',
        'flow_stage_id' => 'nullable|exists:flow_stages,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}