<?php

namespace NextDeveloper\CRM\Authorization\Roles;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use NextDeveloper\Commons\Helpers\DatabaseHelper;
use NextDeveloper\CRM\Database\Models\AccountManagers;
use NextDeveloper\CRM\Database\Models\Campaigns;
use NextDeveloper\CRM\Database\Models\UserManagers;
use NextDeveloper\CRM\Helpers\CampaignPerspectiveHelper;
use NextDeveloper\CRM\Helpers\CustomerUsersAccessHelper;
use NextDeveloper\IAM\Authorization\Roles\AbstractRole;
use NextDeveloper\IAM\Authorization\Roles\IAuthorizationRole;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\IAM\Helpers\UserHelper;

class SalesManagerRole extends AbstractRole implements IAuthorizationRole
{
    public const NAME = 'sales-manager';

    public const LEVEL = 120;

    public const DESCRIPTION = 'Sales Manager';

    public const DB_PREFIX = 'crm';

    /**
     * Applies basic member role sql for Eloquent
     *
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        // Customer Lists / Customers tabs of a campaign (leo4#1391): the rows of the campaigns this role sees in
        // crm_campaigns, i.e. own campaigns of the current account, or all of them when marketing rules apply.
        if (CampaignPerspectiveHelper::isCampaignPerspective($model)) {
            CampaignPerspectiveHelper::apply($builder, $model, ! UserHelper::has(MarketingManagerRole::NAME));

            return;
        }

        // Same level (120) as marketing-manager: when this role is picked for a user that also holds
        // marketing-manager, the marketing tables keep marketing's rules (no row filter) instead of the
        // iam_user_id = me default at the bottom of this method.
        if (
            ($model->getTable() == 'crm_campaigns' ||
            $model->getTable() == 'crm_campaign_targets' ||
            $model->getTable() == 'crm_targets' ||
            $model->getTable() == 'crm_target_users') &&
            UserHelper::has(MarketingManagerRole::NAME)
        ) {
            return;
        }

        if ($model->getTable() == 'crm_accounts_perspective' || $model->getTable() == 'crm_accounts') {
            $builder->whereRaw('id IN       (
                select distinct crm_account_id from crm_account_managers cam where cam.iam_account_id = '.UserHelper::currentAccount()->id.'
            )');

            return;
        }

        if (
            $model->getTable() == 'crm_opportunities_perspective' ||
            $model->getTable() == 'crm_opportunities' ||
            $model->getTable() == 'crm_quotes' ||
            $model->getTable() == 'crm_quotes_perspective' ||
            $model->getTable() == 'crm_projects' ||
            // Tasks on a project board are shared by the account's team (leo4#1242).
            $model->getTable() == 'crm_tasks' ||
            // Email templates are shared by the account's team and never leave the account (leo4#1339).
            $model->getTable() == 'crm_email_templates'
        ) {
            $builder->where('iam_account_id', UserHelper::currentAccount()->id);

            return;
        }

        if ($model->getTable() == 'crm_users_perspective') {
            // A customer's users are sign-in identities: only platform staff and partners see them (leo4#1388).
            if (CustomerUsersAccessHelper::hideUsersFromNonPartners($builder)) {
                return;
            }

            // The people of the CRM accounts the acting account is assigned to as manager, and of its own CRM account
            // (an account owner works on the users of their own account). Soft deleted manager rows do not count.
            $currentAccountId = (int) UserHelper::currentAccount()->id;
            $builder->whereRaw('(crm_account_id in (
                select cam.crm_account_id from crm_account_managers cam
                where cam.iam_account_id = '.$currentAccountId.' and cam.deleted_at is null
            ) or crm_account_id in (
                select ca.id from crm_accounts ca where ca.iam_account_id = '.$currentAccountId.' and ca.deleted_at is null
            ))');

            return;
        }

        // Sales staff list: no row filter here. The view has an iam_user_id column, so the default below would show
        // a sales person/manager only their own row. SalesPeopleScope already limits the list to the current account.
        if ($model->getTable() == 'crm_sales_people_perspective') {
            return;
        }

        $isUserIdExists = DatabaseHelper::isColumnExists($model->getTable(), 'iam_user_id');

        /**
         * Here the user will only be able to run this query only if the table name starts with 'crm_*' and
         * the owner of the model is the user itself.
         */
        if ($isUserIdExists) {
            $builder->where('iam_user_id', UserHelper::me()->id);
        }
    }

    public function checkPrivileges(?Users $users = null)
    {
        // return UserHelper::hasRole(self::NAME, $users);
    }

    public function getModule()
    {
        return 'crm';
    }

    public function allowedOperations(): array
    {
        return [
            'crm_accounts:read',
            'crm_accounts:create',
            'crm_accounts:update',
            'crm_users:read',
            'crm_users:create',
            'crm_users:update',
            'crm_account_managers:read',
            'crm_account_managers:create',
            'crm_account_managers:delete',
            'crm_accounts_perspective:read',
            'crm_account_users_perspective:read',
            'crm_ideal_customer_profiles:read',
            'crm_ideal_customer_profiles:create',
            'crm_ideal_customer_profiles:update',
            'crm_ideal_customer_profiles:delete',
            'crm_campaigns:read',
            // Mailing analytics (stats, per-email, replies) - see CampaignAnalyticsService
            'crm_campaign_analytics:read',
            'crm_campaigns:create',
            'crm_campaigns:update',
            'crm_campaigns:delete',
            'crm_campaign_targets:read',
            'crm_campaign_targets:create',
            'crm_campaign_targets:update',
            'crm_campaign_targets:delete',
            'crm_targets:read',
            'crm_targets:create',
            'crm_targets:update',
            'crm_targets:delete',
            'crm_target_users:read',
            'crm_target_users:create',
            'crm_target_users:update',
            'crm_target_users:delete',
            // Customer Lists / Customers tabs of a campaign, scoped in apply() (leo4#1391)
            'crm_campaign_targets_perspective:read',
            'crm_campaign_target_users_perspective:read',
            'crm_opportunities:read',
            'crm_opportunities:create',
            'crm_opportunities:update',
            'crm_opportunities:delete',
            'crm_user_managers:read',
            'crm_user_managers:create',
            'crm_user_managers:update',
            'crm_user_managers:delete',
            'crm_users_perspective:read',
            'crm_quotes:read',
            'crm_quotes:create',
            'crm_quotes:update',
            'crm_quotes:delete',
            'crm_quote_items:read',
            'crm_quote_items:create',
            'crm_quote_items:update',
            'crm_quote_items:delete',
            'crm_opportunities:read',
            'crm_opportunities:create',
            'crm_opportunities:update',
            'crm_opportunities:delete',
            'crm_calls:read',
            'crm_calls:create',
            'crm_calls:update',
            'crm_calls:delete',
            'crm_emails:read',
            'crm_emails:create',
            'crm_emails:update',
            'crm_emails:delete',
            // Mailing templates of the account (leo4#1339). A manager must hold at least what a sales person holds,
            // because only the lowest level crm role of a user is checked.
            'crm_email_templates:read',
            'crm_email_templates:create',
            'crm_email_templates:update',
            'crm_email_templates:delete',
            'crm_tasks:read',
            'crm_tasks:create',
            'crm_tasks:update',
            'crm_tasks:delete',
            'crm_notes:read',
            'crm_notes:create',
            'crm_notes:update',
            'crm_notes:delete',
            // Contacts of a CRM account (Agenda contacts linked to accounts) and who they are shared with
            'crm_account_contacts:read',
            'crm_account_contacts:create',
            'crm_account_contacts:update',
            'crm_account_contacts:delete',
            'crm_account_contact_shares:read',
            'crm_account_contact_shares:create',
            'crm_account_contact_shares:delete',
            // Lead importer: managers/admins manage connections, runs are read-only audit
            'crm_importers:read',
            'crm_importers:create',
            'crm_importers:update',
            'crm_importers:delete',
            'crm_importer_runs:read',
            'crm_meetings:read',
            'crm_meetings:create',
            'crm_meetings:update',
            'crm_meetings:delete',
            // Projects (external repos / boards). The token is write-only; see Projects model.
            'crm_projects:read',
            'crm_projects:create',
            'crm_projects:update',
            'crm_projects:delete',

            'crm_opportunities_performance:read',
            'crm_leo_active_customers_perspective:read',

            'crm_sales_people_perspective:read',
            'crm_stats_performances_perspective:read',
            'crm_daily_new_accounts_performance:read',
            'crm_weekly_new_accounts_performance:read',
            'crm_monthly_new_accounts_performance:read',
            'crm_monthly_new_accounts_per_dist_performance:read',
            'crm_monthly_paying_customers_performance:read',
            'crm_account_managers_performance:read',
        ];
    }

    public function checkDeletePolicy(Model $model, Users $user): bool
    {
        // Projects, tasks and email templates belong to the account they were created in.
        if (
            $model->getTable() == 'crm_projects' ||
            $model->getTable() == 'crm_tasks' ||
            $model->getTable() == 'crm_email_templates'
        ) {
            return $model->iam_account_id == UserHelper::currentAccount()->id;
        }

        // Opportunities belong to the account (team) they were created in, so a sales manager can delete
        // the ones created by any team mate in the current account. Before this every opportunity delete
        // by a manager was refused by the final "return false".
        if ($model->getTable() == 'crm_opportunities' || $model->getTable() == 'crm_opportunities_perspective') {
            return $model->iam_account_id == UserHelper::currentAccount()->id;
        }

        // Campaigns belong to the account (team) they were created in (leo4#1390). Before this every campaign delete
        // by a manager was refused by the final "return false", although crm_campaigns:delete is allowed.
        if ($model->getTable() == 'crm_campaigns') {
            return $this->isRecordOfCurrentAccount($model);
        }

        if ($model->getTable() == 'crm_campaign_targets') {
            $campaign = Campaigns::withoutGlobalScopes()
                ->where('id', $model->crm_campaign_id)
                ->first();

            return $campaign && $campaign->iam_account_id == UserHelper::currentAccount()->id;
        }

        // Every other table this role may delete (quote lines, quotes, notes, calls, meetings, ...) carries iam_account_id:
        // a record of the current account may be deleted. Before this the final "return false" refused all of them,
        // so removing a product line from an opportunity failed with 422 (leo4#1395).
        if (in_array($model->getTable().':delete', $this->allowedOperations(), true)
            && DatabaseHelper::isColumnExists($model->getTable(), 'iam_account_id')) {
            return $this->isRecordOfCurrentAccount($model);
        }

        return false;
    }

    public function checkUpdatePolicy(Model $model, Users $users): bool
    {
        // A campaign stays in its account, also when the manager created it while acting in another account, so the
        // iam_user_id fallback at the bottom does not apply to it (leo4#1390).
        if ($model->getTable() == 'crm_campaigns') {
            return $this->isRecordOfCurrentAccount($model);
        }

        // Projects and tasks belong to the account they were created in, so any sales manager there can update them.
        if ($model->getTable() == 'crm_projects' || $model->getTable() == 'crm_tasks') {
            return $model->iam_account_id == UserHelper::currentAccount()->id;
        }

        // An email template stays in its account: it must belong to the current account before and after the change,
        // even when the manager created it while acting in another account (leo4#1339).
        if ($model->getTable() == 'crm_email_templates') {
            $accountId = UserHelper::currentAccount()->id;

            return $model->getAttribute('iam_account_id') == $accountId
                && $model->getOriginal('iam_account_id', $accountId) == $accountId;
        }

        if ($model->getTable() == 'crm_accounts') {
            $amIManager = AccountManagers::withoutGlobalScopes()
                ->where('iam_account_id', UserHelper::currentAccount()->id)
                ->where('crm_account_id', $model->id)
                ->first();

            if (config('leo.debug.authorization_scope')) {
                Log::info('SalesManagerRole::checkUpdatePolicy', ['amIManager' => $amIManager]);
            }

            return $amIManager !== null;
        }

        if ($model->getTable() == 'crm_users') {
            $amIManager = UserManagers::withoutGlobalScopes()
                ->where('iam_user_id', UserHelper::currentUser()->id)
                ->where('crm_user_id', $model->id)
                ->first();

            if (config('leo.debug.authorization_scope')) {
                Log::info('SalesManagerRole::checkUpdatePolicy', ['amIManager' => $amIManager]);
            }

            return $amIManager !== null;
        }

        //  Records of the manager's own account may be updated, including handing them to a teammate (iam_user_id).
        //  The record has to belong to the current account both before and after the change, so it can not be
        //  moved into or out of the account.
        $accountId = $model->getAttribute('iam_account_id');

        if ($accountId !== null
            && $accountId == UserHelper::currentAccount()->id
            && $model->getOriginal('iam_account_id', $accountId) == $accountId) {
            return true;
        }

        if ($model->iam_user_id == $users->id) {
            return true;
        }

        return false;
    }

    /**
     * A record may be changed or removed only while it belongs to the current account, both before and after the
     * change, so it can not be moved into or out of the account.
     */
    private function isRecordOfCurrentAccount(Model $model): bool
    {
        $accountId = UserHelper::currentAccount()->id;

        return $model->getAttribute('iam_account_id') == $accountId
            && $model->getOriginal('iam_account_id', $accountId) == $accountId;
    }

    public function getLevel(): int
    {
        return self::LEVEL;
    }

    public function getDescription(): string
    {
        return self::DESCRIPTION;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function canBeApplied($column)
    {
        if (self::DB_PREFIX === '*') {
            return true;
        }

        if (Str::startsWith($column, self::DB_PREFIX)) {
            return true;
        }

        return false;
    }

    public function getDbPrefix()
    {
        return self::DB_PREFIX;
    }

    public function checkRules(Users $users): bool
    {
        // TODO: Implement checkRules() method.
    }
}
