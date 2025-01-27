<?php

namespace NextDeveloper\CRM\Actions\QuoteItems;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\CRM\Actions\Quotes\RecalculateQuote;
use NextDeveloper\CRM\Database\Models\QuoteItems;
use NextDeveloper\CRM\Database\Models\Quotes;
use NextDeveloper\CRM\Database\Models\Users;

/**
 * This action, assigns an account manager to the given user
 */
class ValidateQuoteItem extends AbstractAction
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * This action takes a user object and assigns an Account Manager
     *
     * @param Users $users
     */
    public function __construct(QuoteItems $item, $params = null, $previousAction = null)
    {
        $this->model = $item;

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
