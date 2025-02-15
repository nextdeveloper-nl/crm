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
