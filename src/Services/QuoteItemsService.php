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
        // Multi-tenancy: the quote must be visible to the caller. The generated create() resolves the quote uuid
        // without checking, so a foreign quote would receive the item before the (scoped) recalculation fails.
        $data = \NextDeveloper\CRM\Helpers\TenantHelper::guardWrite($data);

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
            } elseif ($productCatalog) {
                //  Many plans carry no currency of their own; they are priced in their market's currency.
                $marketCurrencyId = self::marketCurrencyIdOf($productCatalog->marketplace_product_id);

                if ($marketCurrencyId) {
                    $data['common_currency_id'] = $marketCurrencyId;
                }
            }
        }

        //  Last resort, as the comment above says: inherit the currency of the parent quote.
        if (!array_key_exists('common_currency_id', $data) && isset($quote) && $quote && $quote->common_currency_id) {
            $data['common_currency_id'] = $quote->common_currency_id;
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
        // Multi-tenancy: an item cannot be moved onto a quote the caller cannot see.
        $data = \NextDeveloper\CRM\Helpers\TenantHelper::guardWrite($data);

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

        // Synchronous like create/update, so the opportunity value the panel reloads right after is already the new
        // sum; removing the last product writes 0 (leo4#1395).
        (new RecalculateQuote($quote, syncEmptyTotal: true))->handle();

        return $line;
    }

    /**
     * The currency of the market a product is sold in, read without authorization scopes: the plan was already
     * resolved above and the market currency is not sensitive.
     */
    private static function marketCurrencyIdOf(?int $productId): ?int
    {
        if (! $productId) {
            return null;
        }

        $currencyId = \Illuminate\Support\Facades\DB::table('marketplace_products as p')
            ->join('marketplace_markets as m', 'm.id', '=', 'p.marketplace_market_id')
            ->where('p.id', $productId)
            ->value('m.common_currency_id');

        return $currencyId ? (int) $currencyId : null;
    }
}
