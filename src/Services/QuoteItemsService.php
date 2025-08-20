<?php

namespace NextDeveloper\CRM\Services;

use NextDeveloper\CRM\Actions\QuoteItems\ValidateQuoteItem;
use NextDeveloper\CRM\Actions\Quotes\RecalculateQuote;
use NextDeveloper\CRM\Database\Models\Quotes;
use NextDeveloper\CRM\Services\AbstractServices\AbstractQuoteItemsService;

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
        if (!array_key_exists('common_currency_id', $data) && array_key_exists('crm_quote_id', $data)) {
            $quoteId = $data['crm_quote_id'];
            $quote = Quotes::where('id', is_numeric($quoteId) ? $quoteId : null)
                ->when(!is_numeric($quoteId), fn($q) => $q->orWhere('uuid', $quoteId))
                ->first();

            if ($quote && $quote->common_currency_id) {
                $data['common_currency_id'] = $quote->common_currency_id;
            }
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
        $line = parent::delete($id);

        $quote = Quotes::where('id', $line->crm_quote_id)->first();

        (new RecalculateQuote($quote))->handle();

        return $line;
    }
}
