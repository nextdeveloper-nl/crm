<?php

namespace NextDeveloper\CRM\Services;

use NextDeveloper\CRM\Actions\QuoteItems\ValidateQuoteItem;
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

        dispatch(new ValidateQuoteItem($line));

        return $line;
    }
}
