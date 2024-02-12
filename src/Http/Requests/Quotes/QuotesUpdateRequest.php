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
            'crm_opportunities_id' => 'nullable|exists:crm_opportunities,uuid|uuid',
        'name' => 'nullable|string',
        'description' => 'nullable|string',
        'amount' => '',
        'detailed_amount' => 'nullable|string',
        'suggested_price' => 'nullable|numeric',
        'suggested_currency_code' => '',
        'status' => '',
        'tags' => '',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n\n\n
}