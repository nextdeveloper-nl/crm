<?php

namespace NextDeveloper\CRM\Services;

use NextDeveloper\CRM\Actions\QuoteItems\ValidateQuoteItem;
use NextDeveloper\CRM\Jobs\Quotes\RecalculateQuote;
use NextDeveloper\CRM\Database\Models\QuoteItems;
use NextDeveloper\CRM\Database\Models\Quotes;
use NextDeveloper\CRM\Services\AbstractServices\AbstractQuoteItemsService;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;

/**
 * This class is responsible from managing the data for QuoteItems
 *
 * Class QuoteItemsService.
 *
 * @package NextDeveloper\CRM\Database\Models
 */
class QuoteItemsService extends AbstractQuoteItemsService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    public static function create($data)
    {
        // If currency not provided, inherit from parent quote
        if (array_key_exists('crm_quote_id', $data)) {
            $quoteId = $data['crm_quote_id'];
            $quote = Quotes::where('id', is_numeric($quoteId) ? $quoteId : null)
                ->when(!is_numeric($quoteId), fn($q) => $q->orWhere('uuid', $quoteId))
                ->first();
        }

        if (!array_key_exists('common_currency_id', $data) && array_key_exists('marketplace_product_catalog_id', $data)) {
            $productCatalogId = $data['marketplace_product_catalog_id'];
            $productCatalog = \NextDeveloper\Marketplace\Database\Models\ProductCatalogs::withoutGlobalScope(AuthorizationScope::class)
                ->where('id', is_numeric($productCatalogId) ? $productCatalogId : null)
                ->when(!is_numeric($productCatalogId), fn($q) => $q->orWhere('uuid', $productCatalogId))
                ->first();

            if ($productCatalog && $productCatalog->common_currency_id) {
                $data['common_currency_id'] = $productCatalog->common_currency_id;
            }
        }

        if (!array_key_exists('common_currency_id', $data)) {
            throw new \RuntimeException('Product catalog currency not found.');
        }

        $line = parent::create($data);

        (new ValidateQuoteItem($line))->handle();

        $quote = Quotes::where('id', $line->crm_quote_id)->first();

        (new RecalculateQuote($quote))->handle();

        return $line;
    }

    public static function update($id, $data)
    {
        $line = parent::update($id, $data);

        (new ValidateQuoteItem($line))->handle();

        $quote = Quotes::where('id', $line->crm_quote_id)->first();

        (new RecalculateQuote($quote))->handle();

        return $line;
    }

    public static function delete($id)
    {
        $quoteItem = QuoteItems::withoutGlobalScope(AuthorizationScope::class)
            ->where('uuid', $id)
            ->first();

        $quote = Quotes::where('id', $quoteItem->crm_quote_id)->first();

        $line = parent::delete($id);

        dispatch(new RecalculateQuote($quote));

        return $line;
    }
}
