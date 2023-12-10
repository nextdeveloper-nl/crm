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
            'crm_opportunities_id'    => 'nullable|exists:crm_opportunities,uuid|uuid',
        'name'                    => 'nullable|string|max:150',
        'description'             => 'nullable|string|max:2000',
        'amount'                  => 'numeric',
        'detailed_amount'         => 'nullable|string',
        'suggested_price'         => 'nullable|numeric',
        'suggested_currency_code' => 'string|max:3',
        'status'                  => '',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n\n\n
}