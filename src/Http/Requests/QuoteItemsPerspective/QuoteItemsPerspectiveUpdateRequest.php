<?php

namespace NextDeveloper\CRM\Http\Requests\QuoteItemsPerspective;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class QuoteItemsPerspectiveUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'marketplace_product_id' => 'nullable|exists:marketplace_products,uuid|uuid',
        'product_name' => 'nullable|string',
        'marketplace_product_catalog_id' => 'nullable|exists:marketplace_product_catalogs,uuid|uuid',
        'product_catatalog_name' => 'nullable|string',
        'currency_code' => 'nullable|string',
        'quantity' => 'nullable|integer',
        'discount' => 'nullable',
        'unit_price' => 'nullable',
        'total_price' => 'nullable',
        'crm_quote_id' => 'nullable|exists:crm_quotes,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}