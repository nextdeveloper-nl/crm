<?php

namespace NextDeveloper\CRM\Actions\Quotes;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use NextDeveloper\Accounting\Services\InvoicesService;
use NextDeveloper\Accounting\Services\InvoiceItemsService;
use Helpers\AccountingHelper;
use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Database\Models\Currencies;
use NextDeveloper\Commons\Database\Models\ExchangeRates;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\CRM\Database\Models\Quotes;
use NextDeveloper\CRM\Database\Models\QuoteItems;

/**
 * This action converts a quote to an invoice by creating an invoice record
 * and transferring the relevant data from the quote.
 *
 * Note: Look for the RecalculateQuote action for get information our action workflow.
 *
 * Look at services for more information about the invoice creation process.
 */
class ConvertQuoteToInvoice extends AbstractAction
{

    /**
     * ConvertQuoteToInvoice constructor.
     *
     * @param Quotes $quote The quote to convert to an invoice.
     * @param array|null $params Optional parameters for the conversion.
     * @param mixed|null $previousAction Previous action if chained.
     *
     * @throws NotAllowedException If the quote cannot be converted.
     */
    public function __construct(Quotes $quote, $params = null, mixed $previousAction = null)
    {
        $this->queue = 'crm';
        $this->model = $quote;

        // Validate that the quote can be converted
        if ($quote->approval_level !== 'approved' || $quote->is_converted) {
            throw new NotAllowedException('Quote must be approved before converting to invoice.');
        }

        parent::__construct($params, $previousAction);
    }

    /**
     * Handle the conversion process.
     *
     * This method creates an invoice and transfers quote items to invoice items.
     * @throws \Throwable
     */
    public function handle(): void
    {
        $this->setProgress(0, 'Starting quote to invoice conversion.');

        // Get the accounting account for the IAM account
        $accountingAccount = AccountingHelper::getAccountFromIamAccountId($this->model->iam_account_id);

        if (!$accountingAccount) {
            $this->setProgress(0, 'Cannot find accounting account for the quote.');
            return;
        }

        $this->setProgress(20, 'Creating invoice record.');

        DB::beginTransaction();

        // Create the invoice
        try {
            $invoiceData = [
                'accounting_account_id' => $accountingAccount->id,
                'amount' => $this->model->total_amount,
                'common_currency_id' => $this->model->common_currency_id,
                'exchange_rate' => $this->exchangeRate($this->model->common_currency_id),
                'vat' => 0, // Default VAT, can be calculated later
                'is_paid' => false,
                'is_refund' => false,
                'is_payable' => true,
                'is_sealed' => false,
                'due_date' => Carbon::now()->addDays(30), // 30 days payment term
                'iam_account_id' => $this->model->iam_account_id,
                'iam_user_id' => $this->model->iam_user_id,
                'term_year' => Carbon::now()->year,
                'term_month' => Carbon::now()->month,
                'is_cancelled' => false,
                'note' => 'Generated from quote: ' . $this->model->name
            ];

            $invoice = InvoicesService::create($invoiceData);

            $this->setProgress(50, 'Invoice created successfully. Transferring quote items.');
            Log::info('Converting quote to invoice', [
                'quote_id' => $this->model->id,
                'invoice_id' => $invoice->id,
                'iam_account_id' => $this->model->iam_account_id
            ]);

            // Get quote items and convert them to invoice items
            $quoteItems = QuoteItems::where('crm_quote_id', $this->model->id)->get();

            $itemCount = $quoteItems->count();
            $stepSize = $itemCount > 0 ? 40 / $itemCount : 40;
            $currentProgress = 50;

            foreach ($quoteItems as $quoteItem) {
                $this->setProgress($currentProgress, 'Converting quote item: ' . $quoteItem->id);

                $invoiceItemData = [
                    'accounting_invoice_id' => $invoice->id,
                    'object_type' => 'NextDeveloper\Marketplace\Database\Models\ProductCatalogs',
                    'object_id' => $quoteItem->marketplace_product_catalog_id,
                    'quantity' => $quoteItem->quantity,
                    'unit_price' => $quoteItem->unit_price,
                    'common_currency_id' => $this->model->common_currency_id,
                    'iam_account_id' => $this->model->iam_account_id,
                    'accounting_account_id' => $accountingAccount->id,
                    'details' => [
                        'quote_item_id' => $quoteItem->id,
                        'marketplace_product_id' => $quoteItem->marketplace_product_id,
                        'discount' => $quoteItem->discount ?? 0
                    ]
                ];

                InvoiceItemsService::create($invoiceItemData);

                $currentProgress += $stepSize;
            }

            $this->setProgress(95, 'Finalizing conversion.');

            // Update the quote to mark it as converted
            $this->model->updateQuietly([
                'is_converted' => true
            ]);

            $this->setProgress(100, 'Quote successfully converted to invoice: ' . $invoice->uuid);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->setProgress(0, 'Error converting quote to invoice: ' . $e->getMessage());
            return;
        }
    }

    /**
     * Get the exchange rate for the given currency.
     *
     * @param int $commonCurrencyId
     * @return float
     */
    protected function exchangeRate(int $commonCurrencyId): float
    {
        $currencies = Currencies::where('id', $commonCurrencyId)->first();

        if (!$currencies) {
            return 1.0; // Default exchange rate if not found
        }

        // Assuming the exchange rate is stored in the Currencies model
        $exchangeRate = ExchangeRates::where('reference_currency_code', $currencies->code)->first();

        return $exchangeRate ? $exchangeRate->rate : 1.0;
    }
}
