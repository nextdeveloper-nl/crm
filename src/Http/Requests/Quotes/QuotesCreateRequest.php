<?php

namespace NextDeveloper\CRM\Http\Requests\Quotes;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class QuotesCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'crm_opportunities_id' => 'nullable|exists:crm_opportunities,uuid|uuid',
        'name' => 'required|string',
        'description' => 'nullable|string',
        'suggested_price' => 'nullable',
        'common_currency_id' => 'nullable|exists:common_currencies,uuid|uuid',
        'approval_level' => '',
        'tags' => '',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n\n\n
}