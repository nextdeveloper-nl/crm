<?php

namespace NextDeveloper\CRM\Services;

use NextDeveloper\Commons\Services\CurrenciesService;
use NextDeveloper\Communication\Helpers\Communicate;
use NextDeveloper\CRM\Database\Models\Opportunities;
use NextDeveloper\CRM\Database\Models\Quotes;
use NextDeveloper\CRM\Services\AbstractServices\AbstractOpportunitiesService;
use NextDeveloper\Events\Services\Events;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\IAM\Helpers\UserHelper;

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

    public static function create($data)
    {
        $responsible = self::getNaturalResponsibleForOpportunityType($data['type']);

        if($data['type'] == 'business development') {
            $data['type'] = 'business-development';
        }

        $opportunity = parent::create($data);
        $opportunity = $opportunity->refresh();

        if($responsible) {
            $opportunity->update([
                'iam_user_id' => $responsible->id,
            ]);

            $crmAccount = AccountsService::getById($opportunity->crm_account_id);

            AccountManagersService::assignAccountManagerToCrmAccount(
                $crmAccount,
                $opportunity->iam_account_id,
                $responsible->id
            );

            (new Communicate($responsible))->sendNotification(
                subject: 'New ' . $data['type'] . ' opportunity assigned',
                message: 'A new ' . $data['type'] . ' opportunity "' . $data['name'] . '" has been assigned to you.'
                . ' Please review it at your earliest convenience. ' . PHP_EOL . PHP_EOL
                . 'You can reach the opportunity details here: '
                . config('leo.panel_url') . '/crm/opportunities/' . $opportunity->uuid
            );
        }

        return $opportunity->fresh();
    }

    public static function createQuote(Opportunities $opportunity)
    {
        return QuotesService::create([
            'name' => $opportunity->name,
            'crm_opportunity_id' => $opportunity->id,
            'iam_user_id' => $opportunity->iam_user_id,
            'iam_account_id' => $opportunity->iam_account_id,
            'common_currency_id' => CurrenciesService::getDefaultCurrency()->id
        ]);
    }

    public static function getQuote(Opportunities $opportunity): Quotes
    {
        $quote = Quotes::where('crm_opportunity_id', $opportunity->id)
            ->orderBy('id', 'desc')
            ->first();

        return $quote ?? self::createQuote($opportunity);
    }

    public static function getQuoteByOpportunityId($uuid): ?Quotes
    {
        $opportunity = Opportunities::where('id', $uuid)->first();

        if ($opportunity)
            return self::getQuote($opportunity);

        return null;
    }

    public static function getNaturalResponsibleForOpportunityType($type) : ?Users
    {
        $responsible = null;

        if($type == null) {
            return $responsible;
        }

        if (
            strtolower($type) == 'business development' ||
            strtolower($type) == 'partnership' ||
            strtolower($type) == 'business-development'
        ) {
            $businessDevelopers = UserHelper::getUsersWithRole('business-development-representative', UserHelper::currentAccount());

            //  Assigning a random business developer
            if (count($businessDevelopers) > 0) {
                $responsible = $businessDevelopers->random();
            }
        }

        if(strtolower($type) == 'sales') {
            $accountManagers = UserHelper::getUsersWithRole('sales-person', UserHelper::currentAccount());

            //  Assigning a random account manager
            if(count($accountManagers) > 0) {
                $responsible = $accountManagers->random();
            }
        }

        if(!$responsible) {
            $admins = UserHelper::getUsersWithRole('sales-admin', UserHelper::currentAccount());

            if (count($admins) > 0) {
                $responsible = $admins->random();
            }
        }

        return $responsible;
    }
}
