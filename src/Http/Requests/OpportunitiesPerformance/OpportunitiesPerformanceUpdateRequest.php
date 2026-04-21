<?php

namespace NextDeveloper\CRM\Http\Requests\OpportunitiesPerformance;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class OpportunitiesPerformanceUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'leads_count' => 'nullable|integer',
        'prospect_count' => 'nullable|integer',
        'qualification_count' => 'nullable|integer',
        'research_count' => 'nullable|integer',
        'need_analysis_count' => 'nullable|integer',
        'approach_count' => 'nullable|integer',
        'value_proposition_count' => 'nullable|integer',
        'identifying_decision_makers_count' => 'nullable|integer',
        'proposal_count' => 'nullable|integer',
        'negotiation_count' => 'nullable|integer',
        'won_count' => 'nullable|integer',
        'lost_count' => 'nullable|integer',
        'cancelled_count' => 'nullable|integer',
        'perception_analysis_count' => 'nullable|integer',
        'renewal_count' => 'nullable|integer',
        'type' => 'nullable|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}