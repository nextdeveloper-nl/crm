<?php

namespace NextDeveloper\CRM\Services;

use NextDeveloper\Commons\Services\CurrenciesService;
use NextDeveloper\CRM\Database\Models\Opportunities;
use NextDeveloper\CRM\Database\Models\Quotes;
use NextDeveloper\CRM\Services\AbstractServices\AbstractOpportunitiesService;

/**
 * This class is responsible from managing the data for Opportunities
 *
 * Class OpportunitiesService.
 *
 * @package NextDeveloper\CRM\Database\Models
 */
class OpportunitiesService extends AbstractOpportunitiesService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    public static function createQuote(Opportunities $opportunity)
    {
        return QuotesService::create([
            'name'  =>  $opportunity->name,
            'crm_opportunity_id'    =>  $opportunity->id,
            'iam_user_id'   =>  $opportunity->iam_user_id,
            'iam_account_id'    =>  $opportunity->iam_account_id,
            'common_currency_id'    =>  CurrenciesService::getDefaultCurrency()->id
        ]);
    }

    public static function getQuote(Opportunities $opportunity) : Quotes
    {
        $quote = Quotes::where('crm_opportunity_id', $opportunity->id)
            ->orderBy('id', 'desc')
            ->first();

        return $quote ?? self::createQuote($opportunity);
    }

    public static function getQuoteByOpportunityId($uuid) : ?Quotes
    {
        $opportunity = Opportunities::where('id', $uuid)->first();

        if($opportunity)
            return self::getQuote($opportunity);

        return null;
    }
}
