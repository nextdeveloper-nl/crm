<?php

namespace NextDeveloper\CRM\Http\Requests\QuoteLines;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class QuoteLinesCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'crm_quote_id' => 'required|exists:crm_quotes,uuid|uuid',
            'marketplace_product_id' => 'required|exists:marketplace_products,uuid|uuid',
            'marketplace_product_catalog_id' => 'required|exists:marketplace_product_catalogs,uuid|uuid',
            'quantity' => 'required|integer',
            'unit_price' => '',
            'discount' => '',
            'total_price' => '',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}
