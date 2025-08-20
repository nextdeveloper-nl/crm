<?php

namespace NextDeveloper\CRM\Actions\Quotes;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Helpers\ExchangeRateHelper;
use NextDeveloper\Commons\Services\CurrenciesService;
use NextDeveloper\Commons\Services\ExchangeRatesService;
use NextDeveloper\CRM\Database\Models\QuoteItems;
use NextDeveloper\CRM\Database\Models\Quotes;
use NextDeveloper\CRM\Database\Models\Users;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\IAM\Helpers\UserHelper;
use NextDeveloper\Marketplace\Database\Models\ProductCatalogs;
use NextDeveloper\Marketplace\Database\Models\ProductCatalogsPerspective;
use NextDeveloper\Marketplace\Database\Models\ProductsPerspective;

/**
 * This action, assigns an account manager to the given user
 */
class RecalculateQuote extends AbstractAction
{
    /**
     * This action takes a user object and assigns an Account Manager
     *
     * @param Users $users
     */
    public function __construct(Quotes $quote, $params = null, $previousAction = null)
    {
        $this->model = $quote;

        $this->queue = 'crm';

        parent::__construct($params, $previousAction);
    }

    public function handle()
    {
        $this->setProgress(0, 'Approving quote');

        if(UserHelper::hasRole('accounting-manager')) {
            $this->model->update([
                'approval_level' => 'approved',
            ]);
        }
        
        $this->setFinished('Quote approved');
    }
}
