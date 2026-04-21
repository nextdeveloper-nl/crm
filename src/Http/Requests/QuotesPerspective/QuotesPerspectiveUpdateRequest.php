<?php

namespace NextDeveloper\CRM\Http\Requests\QuotesPerspective;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class QuotesPerspectiveUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string',
        'description' => 'nullable|string',
        'total_amount' => 'nullable',
        'detailed_amount' => 'nullable',
        'seller_name' => 'nullable|string',
        'representative_name' => 'nullable|string',
        'buyer_name' => 'nullable|string',
        'common_currency_id' => 'nullable|exists:common_currencies,uuid|uuid',
        'currency_code' => 'nullable|string',
        'suggested_price' => 'nullable',
        'approval_level' => 'nullable',
        'tags' => 'nullable',
        'crm_opportunity_id' => 'nullable|exists:crm_opportunities,uuid|uuid',
        'line_count' => 'nullable|integer',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}