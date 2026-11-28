<?php

namespace NextDeveloper\CRM\Services;

use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\Commons\Services\CurrenciesService;
use NextDeveloper\Communication\Helpers\Communicate;
use NextDeveloper\CRM\Database\Models\AccountManagers;
use NextDeveloper\CRM\Database\Models\Opportunities;
use NextDeveloper\CRM\Database\Models\QuoteItems;
use NextDeveloper\CRM\Database\Models\Quotes;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\CRM\Services\AbstractServices\AbstractOpportunitiesService;
use NextDeveloper\IAM\Database\Models\Accounts as IamAccounts;
use NextDeveloper\IAM\Database\Models\AccountUsers;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\IAM\Helpers\UserHelper;
use NextDeveloper\Flow\Database\Models\Items as FlowItems;
use NextDeveloper\Flow\Services\ItemsService as FlowItemsService;
use Illuminate\Support\Facades\DB;

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

    /**
     * Deletes the opportunity and soft deletes the flow items linked to it (object_type/object_id),
     * so a deleted opportunity does not stay on the flow boards. Flow items are deleted first because
     * their delete permission is delegated to the linked opportunity, which must still be resolvable.
     * Both happen in one transaction so a refused opportunity delete does not leave it without items.
     */
    public static function delete($id)
    {
        $opportunity = Opportunities::where('uuid', $id)->first();

        if (!$opportunity) {
            throw new NotAllowedException(
                'We cannot find the related object to delete. ' .
                'Maybe you dont have the permission to update this object?'
            );
        }

        return DB::transaction(function () use ($opportunity, $id) {
            $items = FlowItems::where('object_type', 'NextDeveloper\CRM\Opportunities')
                ->where('object_id', $opportunity->id)
                ->get();

            foreach ($items as $item) {
                FlowItemsService::delete($item->uuid);
            }

            return parent::delete($id);
        });
    }

    public static function create($data)
    {
        // Multi-tenancy: the CRM account the opportunity is created on must be visible to the caller.
        $data = \NextDeveloper\CRM\Helpers\TenantHelper::guardWrite($data);

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
            // Multi-tenancy: the responsible person and the account managers must come from the tenant that OWNS the
            // opportunity (iam_account_id = the seller), not from the account the code happens to run as. Imports and
            // other system jobs run as the platform admin, so currentAccount() would pick the platform's own staff for
            // a tenant's lead.
            $tenantAccount = IamAccounts::withoutGlobalScope(AuthorizationScope::class)
                ->where('id', $opportunity->iam_account_id)
                ->first() ?? UserHelper::currentAccount();

            // Default to the creator; override with a naturally-assigned person
            // only when the creator is not a sales-person themselves.
            $responsible = UserHelper::me();

            // If iam_user_id was explicitly provided and that user already holds a
            // sales role, honour the caller's assignment — do not overwrite with a
            // randomly picked salesperson.
            $preAssigned = null;
            if (! empty($data['iam_user_id'])) {
                $preAssigned = \NextDeveloper\IAM\Database\Models\Users::withoutGlobalScope(
                        \NextDeveloper\IAM\Database\Scopes\AuthorizationScope::class
                    )
                    ->where('uuid', $data['iam_user_id'])
                    ->first();
            }

            // Multi-tenancy: a pre-assigned user must be a member of the owning tenant; a foreign user uuid is ignored.
            if ($preAssigned && !AccountUsers::withoutGlobalScope(AuthorizationScope::class)
                    ->where('iam_user_id', $preAssigned->id)
                    ->where('iam_account_id', $tenantAccount->id)
                    ->exists()) {
                $preAssigned = null;
            }

            if ($preAssigned &&
                (UserHelper::has('sales-person', $preAssigned) || UserHelper::has('sales-manager', $preAssigned))) {
                $responsible = $preAssigned;
            } elseif (!(UserHelper::has('sales-person') || UserHelper::has('sales-manager'))) {
                $responsible = self::getNaturalResponsibleForOpportunityType($data['type'], $tenantAccount);
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
                $hasSalesRole = UserHelper::getUsersWithRole('sales-person', $tenantAccount)
                    ->whereIn('id', $existingManagerIds)
                    ->isNotEmpty()
                    || UserHelper::getUsersWithRole('sales-manager', $tenantAccount)
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
                    . config('leo.panel_url') . '/crm/opportunities/' . $opportunity->uuid,
                    object: $opportunity,
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

    /**
     * The quote whose lines are the opportunity's products (leo4#1395): its most recent quote. This is the same quote
     * the opportunity perspective reports as latest_quote_amount and the pipeline cards show as the card value.
     * Unscoped on purpose (role, tenant and limit scopes): the income lock and the income sync must see the quote
     * whoever wrote it. withoutGlobalScopes() also drops the soft-delete scope, hence the explicit deleted_at filter.
     */
    public static function getLatestQuote(int $opportunityId): ?Quotes
    {
        return Quotes::withoutGlobalScopes()
            ->whereNull('deleted_at')
            ->where('crm_opportunity_id', $opportunityId)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->first();
    }

    /**
     * True when the opportunity's latest quote has at least one line. Its income is then the quote total and is not
     * typed by hand any more (leo4#1395).
     */
    public static function hasLineItems(int $opportunityId): bool
    {
        $quote = self::getLatestQuote($opportunityId);

        if (!$quote) {
            return false;
        }

        return QuoteItems::withoutGlobalScopes()
            ->whereNull('deleted_at')
            ->where('crm_quote_id', $quote->id)
            ->exists();
    }

    /**
     * Drops a hand-typed income (and its currency) when the opportunity has products: the value is then calculated
     * from the products by RecalculateQuote, which writes it as admin and is not affected by this.
     */
    public static function withoutCalculatedIncome($id, array $data): array
    {
        $incomeKeys = ['income', 'common_currency_id', 'commonCurrencyId'];

        if (UserHelper::bypassRolesCheck() || !array_intersect($incomeKeys, array_keys($data))) {
            return $data;
        }

        $opportunity = Opportunities::where('uuid', $id)->first();

        if (!$opportunity || !self::hasLineItems($opportunity->id)) {
            return $data;
        }

        return array_diff_key($data, array_flip($incomeKeys));
    }

    public static function getQuoteByOpportunityId($uuid): ?Quotes
    {
        $opportunity = Opportunities::where('id', $uuid)->first();

        if ($opportunity)
            return self::getQuote($opportunity);

        return null;
    }

    public static function getNaturalResponsibleForOpportunityType($type, ?IamAccounts $account = null) : ?Users
    {
        $responsible = null;

        // Multi-tenancy: candidates are picked from this account (the tenant that owns the opportunity); the current
        // account only when no tenant is given.
        $account = $account ?? UserHelper::currentAccount();

        if($type == null) {
            return $responsible;
        }

        // getUsersWithRole without an account returns everyone system-wide,
        // so bail early when there is no resolvable current account.
        if(!$account) {
            return null;
        }

        if (
            strtolower($type) == 'business development' ||
            strtolower($type) == 'partnership' ||
            strtolower($type) == 'business-development'
        ) {
            $businessDevelopers = UserHelper::getUsersWithRole('business-development-representative', $account);

            // Pick a random business developer from the current account.
            if (count($businessDevelopers) > 0) {
                $responsible = $businessDevelopers->random();
            }
        }

        if(strtolower($type) == 'sales') {
            // If the creator is already a sales-person, assign it to themselves.
            // Multi-tenancy: only when the creator is acting inside the owning tenant (a platform sales person
            // running an import for a tenant must not get the tenant's lead).
            if(UserHelper::has('sales-person') && (int) UserHelper::currentAccount()->id === (int) $account->id)
                return UserHelper::me();

            $accountManagers = UserHelper::getUsersWithRole('sales-person', $account);

            // Pick a random sales-person from the current account.
            if(count($accountManagers) > 0) {
                $responsible = $accountManagers->random();
            }
        }

        // Fall back to a sales-admin when no role-specific person was found.
        if(!$responsible) {
            $admins = UserHelper::getUsersWithRole('sales-admin', $account);

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
        // Multi-tenancy: an opportunity cannot be moved onto another tenant's CRM account.
        $data = \NextDeveloper\CRM\Helpers\TenantHelper::guardWrite($data);

        // Manual value only while the opportunity has no products (leo4#1395).
        $data = self::withoutCalculatedIncome($id, $data);

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
