<?php

namespace NextDeveloper\CRM\Http\Requests\Quotes;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class QuotesUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'crm_opportunity_id' => 'nullable|exists:crm_opportunities,uuid|uuid',
        'name' => 'nullable|string',
        'description' => 'nullable|string',
        'suggested_price' => 'nullable',
        'common_currency_id' => 'nullable|exists:common_currencies,uuid|uuid',
        'approval_level' => '',
        'tags' => '',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}