<?php

namespace NextDeveloper\CRM\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * Who may work on the users (IAM sign-in identities) of a CRM customer (leo4#1388).
 *
 * On a customer the people are recorded as contacts (crm_account_contacts). Its users are real accounts that can sign
 * in, so only the platform (PlusClouds staff) and partners may see or add them:
 *  - platform staff: acting in the platform account (TenantHelper::platformAccountId()), or holding sales-admin or
 *    system-admin,
 *  - partners: the acting account's accounting account is a reseller, integrator or distributor (the partner flags
 *    that /leo/my/current-account also gives the panel). Affiliates only refer customers and do not count.
 * Trusted server-side callers that bypass the role check are not restricted.
 *
 * Everyone else (a customer's own sales people, who all hold sales-person) sees no users of a customer.
 * leo4 App\Services\CRM\CrmAccountAccessService::canManageCustomerUsers() applies the same rule to adding users;
 * keep the two in step.
 */
class CustomerUsersAccessHelper
{
    public const STAFF_ROLES = ['sales-admin', 'system-admin'];

    public const PARTNER_FLAGS = ['is_reseller', 'is_integrator', 'is_distributor'];

    public static function mayWorkOnCustomerUsers(): bool
    {
        if (UserHelper::bypassRolesCheck()) {
            return true;
        }

        $account = UserHelper::currentAccount();

        if (!$account) {
            return false;
        }

        if (TenantHelper::isPlatformAccount((int) $account->id)) {
            return true;
        }

        foreach (self::STAFF_ROLES as $role) {
            if (UserHelper::has($role)) {
                return true;
            }
        }

        //  Plain query builder: the accounting models carry role scopes of their own.
        return DB::table('accounting_accounts')
            ->where('iam_account_id', $account->id)
            ->whereNull('deleted_at')
            ->where(function ($query) {
                foreach (self::PARTNER_FLAGS as $flag) {
                    $query->orWhere($flag, true);
                }
            })
            ->exists();
    }

    /**
     * Hides every row of crm_users_perspective from a caller that may not work on customer users. Returns true when
     * it did, so the role can stop applying its own filter.
     */
    public static function hideUsersFromNonPartners(Builder $builder): bool
    {
        if (self::mayWorkOnCustomerUsers()) {
            return false;
        }

        $builder->whereRaw('1 = 0');

        return true;
    }
}
