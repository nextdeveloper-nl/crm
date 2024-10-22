<?php

namespace NextDeveloper\CRM\Http\Requests\QuoteLines;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class QuoteLinesUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'crm_quote_id' => 'nullable|exists:crm_quotes,uuid|uuid',
        'marketplace_product_id' => 'nullable|exists:marketplace_products,uuid|uuid',
        'marketplace_product_catalog_id' => 'nullable|exists:marketplace_product_catalogs,uuid|uuid',
        'quantity' => 'nullable|integer',
        'unit_price' => 'nullable',
        'discount' => '',
        'total_price' => 'nullable',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}