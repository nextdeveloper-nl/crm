<?php

namespace NextDeveloper\CRM\Services;

use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\Commons\Services\CurrenciesService;
use NextDeveloper\Communication\Helpers\Communicate;
use NextDeveloper\CRM\Database\Models\AccountManagers;
use NextDeveloper\CRM\Database\Models\Opportunities;
use NextDeveloper\CRM\Database\Models\Quotes;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\CRM\Services\AbstractServices\AbstractOpportunitiesService;
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
        // Normalize legacy type value to the canonical slug form
        if($data['type'] == 'business development') {
            $data['type'] = 'business-development';
        }

        $opportunity = parent::create($data);
        $opportunity = $opportunity->refresh();

        // Campaign-generated opportunities skip responsible assignment entirely.
        // When crm_campaign_id is set the job owns the flow, so we must not
        // touch iam_user_id, account-manager records, or send notifications here.
        if(empty($data['crm_campaign_id'])) {
            // Default to the creator; override with a naturally-assigned person
            // only when the creator is not a sales-person themselves.
            $responsible = UserHelper::me();

            if(!UserHelper::has('sales-person')) {
                $responsible = self::getNaturalResponsibleForOpportunityType($data['type']);
            }

            if($responsible) {
                // Stamp the opportunity with the responsible user under admin rights
                // because the creator may not have permission to write iam_user_id.
                UserHelper::runAsAdmin(function () use ($opportunity, $responsible) {
                    $opportunity->update([
                        'iam_user_id' => $responsible->id,
                    ]);
                });

                $crmAccount = AccountsService::getById($opportunity->crm_account_id);

                // Collect the user IDs of everyone already managing this CRM account.
                $existingManagerIds = AccountManagers::withoutGlobalScope(AuthorizationScope::class)
                    ->where('crm_account_id', $crmAccount->id)
                    ->pluck('iam_user_id')
                    ->toArray();

                // Only assign a new account manager when none of the existing ones
                // already hold a sales-person or sales-manager role, to avoid
                // overwriting an intentional assignment.
                $hasSalesRole = UserHelper::getUsersWithRole('sales-person', UserHelper::currentAccount())
                    ->whereIn('id', $existingManagerIds)
                    ->isNotEmpty()
                    || UserHelper::getUsersWithRole('sales-manager', UserHelper::currentAccount())
                    ->whereIn('id', $existingManagerIds)
                    ->isNotEmpty();

                if (!$hasSalesRole) {
                    AccountManagersService::assignAccountManagerToCrmAccount(
                        $crmAccount,
                        $opportunity->iam_account_id,
                        $responsible->id
                    );
                }

                // Notify the responsible person so they can act on the opportunity.
                (new Communicate($responsible))->sendNotification(
                    severity: 'info',
                    message: 'New ' . $data['type'] . ' opportunity assigned: '
                    . '"' . $data['name'] . '". '
                    . 'Please review it at your earliest convenience. '
                    . config('leo.panel_url') . '/crm/opportunities/' . $opportunity->uuid
                );
            }
        }

        return $opportunity->fresh();
    }

    // Creates a quote for the given opportunity using the default currency.
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

    // Returns the most recent quote for the opportunity, creating one if none exists.
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

        // getUsersWithRole without an account returns everyone system-wide,
        // so bail early when there is no resolvable current account.
        if(!UserHelper::currentAccount()) {
            return null;
        }

        if (
            strtolower($type) == 'business development' ||
            strtolower($type) == 'partnership' ||
            strtolower($type) == 'business-development'
        ) {
            $businessDevelopers = UserHelper::getUsersWithRole('business-development-representative', UserHelper::currentAccount());

            // Pick a random business developer from the current account.
            if (count($businessDevelopers) > 0) {
                $responsible = $businessDevelopers->random();
            }
        }

        if(strtolower($type) == 'sales') {
            // If the creator is already a sales-person, assign it to themselves.
            if(UserHelper::has('sales-person'))
                return UserHelper::me();

            $accountManagers = UserHelper::getUsersWithRole('sales-person', UserHelper::currentAccount());

            // Pick a random sales-person from the current account.
            if(count($accountManagers) > 0) {
                $responsible = $accountManagers->random();
            }
        }

        // Fall back to a sales-admin when no role-specific person was found.
        if(!$responsible) {
            $admins = UserHelper::getUsersWithRole('sales-admin', UserHelper::currentAccount());

            if (count($admins) > 0) {
                $responsible = $admins->random();
            }
        }

        return $responsible;
    }

    /**
     * @throws NotAllowedException
     */
    public static function update($id, array $data)
    {
        $opportunity = parent::update($id, $data);

        // Only sales-managers and sales-admins are allowed to re-assign the
        // responsible user on an existing opportunity.
        if (array_key_exists('iamUserId', $data) &&
            (
                UserHelper::hasRole('sales-manager') ||
                UserHelper::hasRole('sales-admin')
            )
        ) {

            $iamUserId = $data['iamUserId'];

            UserHelper::runAsAdmin(function () use ($opportunity, $iamUserId) {
                //  1) Get user with uuid
                $user = UserHelper::getWithId($iamUserId);
                //  2) Update opportunity with that iam_user_id
                $opportunity->update([
                    'iam_user_id' => $user->id,
                ]);
            });
        }

        return $opportunity->fresh();
    }
}
