<?php

namespace NextDeveloper\CRM\Actions\Quotes;

use Exception;
use Illuminate\Support\Facades\Log;
use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\CRM\Database\Models\Quotes;
use NextDeveloper\CRM\Exceptions\NotReadyException;
use NextDeveloper\CRM\Services\QuotesService;

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
     * @throws NotReadyException If the quote cannot be converted (not approved or already converted).
     */
    public function __construct(Quotes $quote, $params = null, mixed $previousAction = null)
    {
        $this->queue = 'crm';
        $this->model = $quote;

        // Validate that the quote can be converted
        if ($quote->approval_level !== 'approved' || $quote->is_converted) {
            throw new NotReadyException('Quote must be approved before converting to invoice.');
        }

        parent::__construct($params, $previousAction);
    }

    /**
     * Handle the conversion process by delegating to the QuotesService.
     *
     * @throws \Throwable
     */
    public function handle(): void
    {
        $this->setProgress(0, 'Starting quote to invoice conversion.');

        try {
            $invoice = QuotesService::convertToInvoice($this->model);
            $this->setProgress(100, 'Quote successfully converted to invoice: ' . ($invoice->uuid ?? $invoice->id));
        } catch (\Throwable | Exception $e) {
            Log::error('Error converting quote to invoice', [
                'quote_id' => $this->model->id,
                'quote_uuid' => $this->model->uuid ?? null,
                'error' => $e->getMessage(),
            ]);
            $this->setProgress(0, __METHOD__ . ' | Error converting quote to invoice: ' . $e->getMessage());
            return;
        }
    }
}
