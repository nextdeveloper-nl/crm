<?php

namespace NextDeveloper\CRM\Services;

use App\Envelopes\Accounting\QuoteConvertedToInvoiceEnvelope;
use NextDeveloper\Commons\Database\Models\Currencies;
use NextDeveloper\CRM\Services\AbstractServices\AbstractQuotesService;
use Carbon\Carbon;
use Helpers\InvoiceHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use NextDeveloper\Accounting\Helpers\AccountingHelper;
use NextDeveloper\Accounting\Services\InvoiceItemsService;
use NextDeveloper\Accounting\Services\InvoicesService;
use NextDeveloper\Communication\Helpers\Communicate;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\CRM\Database\Models\Opportunities;
use NextDeveloper\CRM\Database\Models\QuoteItems;
use NextDeveloper\CRM\Database\Models\Quotes;
use NextDeveloper\IAM\Helpers\UserHelper;
use NextDeveloper\Marketplace\Database\Models\ProductCatalogs;

/**
 * This class is responsible from managing the data for Quotes
 *
 * Class QuotesService.
 *
 * @package NextDeveloper\CRM\Database\Models
 */
class QuotesService extends AbstractQuotesService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    /**
     * Convert a given quote into an invoice with items, idempotently.
     * Returns the created invoice model.
     *
     * @throws \Throwable
     */
    public static function convertToInvoice(Quotes $quote)
    {
        // Guard: Quote must be approved and not already converted
        if ($quote->approval_level !== 'approved' || $quote->is_converted) {
            throw new \RuntimeException('Quote must be approved and not converted.');
        }

        // Resolve CRM account via opportunity
        if (!$quote->crm_opportunity_id) {
            throw new \RuntimeException('Cannot find CRM opportunity for the quote.');
        }

        $opportunity = Opportunities::withoutGlobalScopes()
            ->where('id', $quote->crm_opportunity_id)
            ->first();

        if (!$opportunity) {
            throw new \RuntimeException('Cannot find CRM opportunity for the quote.');
        }

        $crmAccount = Accounts::withoutGlobalScopes()
            ->where('id', $opportunity->crm_account_id)
            ->first();

        if (!$crmAccount) {
            throw new \RuntimeException('Cannot find CRM account for the quote.');
        }

        $accountingAccount = AccountingHelper::getAccountFromCrmAccount($crmAccount);

        DB::beginTransaction();
        try {
            // Lock the quote row to ensure idempotency
            $lockedQuote = Quotes::withoutGlobalScopes()
                ->lockForUpdate()
                ->find($quote->id);

            if (!$lockedQuote) {
                throw new \RuntimeException('Quote not found during conversion.');
            }

            if ($lockedQuote->is_converted) {
                // Already converted;
                DB::commit();
                return $lockedQuote->invoice ?? (object) ['id' => $lockedQuote->accounting_invoice_id];
            }

            $invoiceData = [
                'accounting_account_id' => $accountingAccount->id,
                'amount' => 0,
                'common_currency_id' => self::defaultCurrencyId(),
                'vat' => 0,
                'is_paid' => false,
                'is_refund' => false,
                'is_payable' => true,
                'is_sealed' => true,
                'due_date' => Carbon::now()->addDays(30),
                'iam_account_id' => $quote->iam_account_id,
                'iam_user_id' => $quote->iam_user_id,
                'term_year' => Carbon::now()->year,
                'term_month' => Carbon::now()->month,
                'is_cancelled' => false,
                'note' => 'Generated from quote: ' . $quote->name . ' (' . ($quote->uuid ?? 'no-uuid') . ')',
            ];

            $invoice = InvoicesService::create($invoiceData);

            Log::info('Converting quote to invoice', [
                'quote_id' => $quote->id,
                'quote_uuid' => $quote->uuid ?? null,
                'invoice_id' => $invoice->id,
                'iam_account_id' => $quote->iam_account_id,
            ]);

            // Create invoice items from quote items
            $quoteItems = QuoteItems::where('crm_quote_id', $quote->id)->get();
            foreach ($quoteItems as $quoteItem) {
                $itemCurrencyId = self::resolveItemCurrency($quoteItem);

                $invoiceItemData = [
                    'accounting_invoice_id' => $invoice->id,
                    'object_type' => 'NextDeveloper\\Marketplace\\Database\\Models\\ProductCatalogs',
                    'object_id' => $quoteItem->marketplace_product_catalog_id,
                    'quantity' => $quoteItem->quantity,
                    'unit_price' => $quoteItem->unit_price * $quoteItem->quantity,
                    'common_currency_id' => $itemCurrencyId,
                    'iam_account_id' => $quote->iam_account_id,
                    'accounting_account_id' => $accountingAccount->id,
                    'details' => [
                        'quote_item_id' => $quoteItem->id,
                        'marketplace_product_id' => $quoteItem->marketplace_product_id,
                        'discount' => $quoteItem->discount ?? 0,
                        'source' => 'crm.quote',
                        'source_quote_id' => $quote->id,
                        'source_quote_uuid' => $quote->uuid ?? null,
                        'original_total_price' => $quoteItem->total_price ?? null,
                    ],
                ];

                InvoiceItemsService::create($invoiceItemData);
            }

            // Mark quote as converted and link invoice
            $quote->updateQuietly([
                'is_converted' => true,
                'accounting_invoice_id' => $invoice->id,
            ]);

            // Update invoice totals
            InvoiceHelper::updateInvoiceAmount($invoice);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error converting quote to invoice', [
                'quote_id' => $quote->id,
                'quote_uuid' => $quote->uuid ?? null,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }

        // Notify accounting admins (outside transaction)
        try {
            $admins = UserHelper::getUsersWithRole('accounting-admin');
            foreach ($admins as $admin) {
                (new Communicate($admin))->sendEnvelope(
                    new QuoteConvertedToInvoiceEnvelope(
                        $quote->name,
                        $invoice->uuid
                    )
                );
            }
        } catch (\Throwable $ex) {
            Log::error('Failed to notify accounting admins: ' . $ex->getMessage());
        }

        return $invoice;
    }

    private static function resolveItemCurrency($item): int
    {
        $catalogue = ProductCatalogs::withoutGlobalScopes()
            ->where('id', $item->marketplace_product_catalog_id)
            ->first();

        if (!$catalogue) {
            throw new \RuntimeException('Product catalog not found for quote item ID: ' . $item->id);
        }

        if (!$catalogue->common_currency_id) {
            throw new \RuntimeException('Product catalog (ID: ' . $catalogue->id . ') does not have a currency set.');
        }

        return $catalogue->common_currency_id;
    }

    private static function defaultCurrencyId(): int
    {
        return Currencies::withoutGlobalScopes()
            ->where('code', 'USD')
            ->first()?->id;
    }

}
