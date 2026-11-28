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
use NextDeveloper\IAM\Authorization\Roles\AbstractRole;
use NextDeveloper\IAM\Authorization\Roles\IAuthorizationRole;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\IAM\Helpers\UserHelper;

class MarketingManagerRole extends AbstractRole implements IAuthorizationRole
{
    public const NAME = 'marketing-manager';

    public const LEVEL = 120;

    public const DESCRIPTION = 'Marketing Manager';

    public const DB_PREFIX = 'crm';

    /**
     * Applies basic member role sql for Eloquent
     *
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (
            $model->getTable() == 'crm_campaigns' ||
            $model->getTable() == 'crm_campaign_targets' ||
            $model->getTable() == 'crm_targets' ||
            $model->getTable() == 'crm_target_users'
        ) {
            return;
        }

        // marketing-manager and sales-manager share level 120, so AuthorizationScope may pick this role for a user
        // that holds both. Everything outside marketing's own tables then has to follow the sales-manager rules,
        // otherwise the default iam_user_id = me filter below hides e.g. the users of the manager's customers.
        if (UserHelper::has(SalesManagerRole::NAME)) {
            app(SalesManagerRole::class)->apply($builder, $model);

            return;
        }

        if (
            $model->getTable() == 'crm_opportunities' ||
            $model->getTable() == 'crm_opportunities_perspective'
        ) {
            $builder->where('iam_account_id', UserHelper::currentAccount()->id);

            return;
        }

        // Email templates are shared by the account's team and never leave the account (leo4#1339).
        if ($model->getTable() == 'crm_email_templates') {
            $builder->where('iam_account_id', UserHelper::currentAccount()->id);

            return;
        }

        /**
         * Here the user will only be able to run this query only if the table name starts with 'crm_*' and
         * the owner of the model is the user itself.
         */
        $isUserIdExists = DatabaseHelper::isColumnExists($model->getTable(), 'iam_user_id');
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
        return array_merge((new SalesManagerRole)->allowedOperations(), [
            'crm_campaigns:read',
            'crm_campaigns:create',
            'crm_campaigns:update',
            'crm_campaigns:delete',

            'crm_campaign_targets:read',
            'crm_campaign_targets:create',
            'crm_campaign_targets:update',
            'crm_campaign_targets:delete',

            'crm_target_users:read',
            'crm_target_users:create',
            'crm_target_users:update',
            'crm_target_users:delete',

            'crm_targets:read',
            'crm_targets:create',
            'crm_targets:update',
            'crm_targets:delete',

            'crm_campaign_target_users_perspective:read',
            'crm_campaign_targets_perspective:read',
            'crm_target_users_perspective:read',
            'crm_user_emails_perspective:read',
        ]);
    }

    public function checkDeletePolicy(Model $model, Users $user): bool
    {
        // Email templates and campaigns belong to the account they were created in (leo4#1339, leo4#1390).
        if ($model->getTable() == 'crm_email_templates' || $model->getTable() == 'crm_campaigns') {
            return $this->isRecordOfCurrentAccount($model);
        }

        if ($model->getTable() == 'crm_campaign_targets') {
            $campaign = Campaigns::withoutGlobalScopes()
                ->where('id', $model->crm_campaign_id)
                ->first();

            return $campaign && $campaign->iam_account_id == UserHelper::currentAccount()->id;
        }

        return false;
    }

    public function checkUpdatePolicy(Model $model, Users $users): bool
    {
        // A campaign of the account may be edited by any of its marketing managers, but never moved into or out of
        // the account (leo4#1390).
        if ($model->getTable() == 'crm_email_templates' || $model->getTable() == 'crm_campaigns') {
            return $this->isRecordOfCurrentAccount($model);
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

        if (property_exists('iam_account_id', $model)) {
            if ($model->iam_account_id == UserHelper::currentAccount()->id) {
                return true;
            }
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
