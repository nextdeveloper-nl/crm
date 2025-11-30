<?php

namespace NextDeveloper\CRM\Actions\QuoteItems;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\CRM\Database\Models\QuoteItems;
use NextDeveloper\CRM\Database\Models\Quotes;
use NextDeveloper\CRM\Database\Models\Users;
use NextDeveloper\CRM\Jobs\Quotes\RecalculateQuote;

/**
 * This action, assigns an account manager to the given user
 */
class ValidateQuoteItem extends AbstractAction
{
    /**
     * This action takes a user object and assigns an Account Manager
     *
     * @param Users $users
     */
    public function __construct(QuoteItems $item, $params = null, $previousAction = null)
    {
        $this->model = $item;

        $this->queue = 'crm';

        parent::__construct($params, $previousAction);
    }

    public function handle()
    {
        $this->setProgress(0, 'Validating quote item');

        $this->setProgress(90, 'Updating the price of the quote');

        $quote = Quotes::where('id', $this->model->crm_quote_id)->first();

        dispatch(new RecalculateQuote($quote));

        $this->setFinished('Quote item validation finished.');
    }
}
