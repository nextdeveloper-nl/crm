<?php

namespace NextDeveloper\CRM\Authorization\Roles;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use NextDeveloper\Commons\Helpers\DatabaseHelper;
use NextDeveloper\CRM\Database\Models\AccountManagers;
use NextDeveloper\CRM\Database\Models\Campaigns;
use NextDeveloper\CRM\Database\Models\Targets;
use NextDeveloper\CRM\Database\Models\UserManagers;
use NextDeveloper\IAM\Authorization\Roles\AbstractRole;
use NextDeveloper\IAM\Authorization\Roles\IAuthorizationRole;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\IAM\Helpers\UserHelper;

class SalesPersonRole extends AbstractRole implements IAuthorizationRole
{
    public const NAME = 'sales-person';

    public const LEVEL = 150;

    public const DESCRIPTION = 'Account Manager';

    public const DB_PREFIX = 'crm';

    /**
     * Applies basic member role sql
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if($model->getTable() == 'crm_accounts_perspective' || $model->getTable() == 'crm_accounts') {
            $builder->whereRaw('id IN       (
                select distinct crm_account_id from crm_account_managers cam where cam.iam_user_id = ' . UserHelper::me()->id . ' and cam.iam_account_id = ' . UserHelper::currentAccount()->id . '
            )');

            return;
        }

        if($model->getTable() == 'crm_users_perspective') {
            $builder->whereRaw('crm_account_id in (
                select cam.crm_account_id from crm_account_managers cam where cam.iam_account_id = ' . UserHelper::currentAccount()->id . '
            )');
            return;
        }

        if(
            $model->getTable() == 'crm_opportunities' ||
            $model->getTable() == 'crm_opportunities_perspective'
        ) {
            $builder->where('iam_account_id', UserHelper::currentAccount()->id)
                ->where('iam_user_id', UserHelper::me()->id);
            return;
        }

        $isUserIdExists =  DatabaseHelper::isColumnExists($model->getTable(), 'iam_user_id');

        /**
         * Here the user will only be able to run this query only if the table name starts with 'crm_*' and
         * the owner of the model is the user itself.
         */
        if($isUserIdExists) $builder->where('iam_user_id', UserHelper::me()->id);
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

    public function checkDeletePolicy(Model $model, Users $user): bool
    {
        // A sales person can delete the opportunities they own in the current account. Before this
        // every opportunity delete was refused (and with it the delete of the linked flow item, since
        // flow_items delegates its delete permission to the linked object).
        if (
            $model->getTable() == 'crm_opportunities' ||
            $model->getTable() == 'crm_opportunities_perspective'
        ) {
            return $model->iam_user_id == UserHelper::me()->id &&
                $model->iam_account_id == UserHelper::currentAccount()->id;
        }

        if ($model->getTable() == 'crm_campaign_targets') {
            $campaign = Campaigns::withoutGlobalScopes()
                ->where('id', $model->crm_campaign_id)
                ->first();

            return $campaign && $campaign->iam_account_id == UserHelper::currentAccount()->id;
        }

        if (! $this->isOperationAllowed($model, 'delete')) {
            return false;
        }

        if ($model->getTable() == 'crm_target_users') {
            return $this->isTargetOwnedByMe($model->crm_target_id);
        }

        // Every other table this role may delete (email templates, tasks, notes, meetings, ...) carries
        // iam_account_id/iam_user_id: a sales person may delete the records they own in the current account.
        return $this->isOwnedByMe($model);
    }

    public function checkUpdatePolicy(Model $model, Users $users) : bool
    {
        // Opportunities (and their perspective) are not crm_accounts rows, so $model->id must not be
        // compared with crm_account_id. Previously this made every opportunity update fail (e.g. the
        // second save of a flow item linked to a new opportunity). The owner of the opportunity, or a
        // manager of the CRM account it belongs to, may update it.
        if (
            $model->getTable() == 'crm_opportunities' ||
            $model->getTable() == 'crm_opportunities_perspective'
        ) {
            if (
                $model->iam_user_id == UserHelper::me()->id &&
                $model->iam_account_id == UserHelper::currentAccount()->id
            ) {
                return true;
            }

            return $model->crm_account_id && AccountManagers::withoutGlobalScopes()
                ->where('iam_user_id', UserHelper::currentUser()->id)
                ->where('iam_account_id', UserHelper::currentAccount()->id)
                ->where('crm_account_id', $model->crm_account_id)
                ->exists();
        }

        if (! $this->isOperationAllowed($model, 'update')) {
            return false;
        }

        // Only crm_accounts rows are matched against crm_account_managers by their own id. Before this every
        // other table fell through to that check, so e.g. a sales person could create email templates but
        // never update them (unless the template id happened to equal a managed CRM account id).
        if ($model->getTable() == 'crm_accounts') {
            return AccountManagers::withoutGlobalScopes()
                ->where('iam_user_id', UserHelper::currentUser()->id)
                ->where('iam_account_id', UserHelper::currentAccount()->id)
                ->where('crm_account_id', $model->id)
                ->exists();
        }

        // crm_users has no iam_account_id; it is managed through crm_user_managers.
        if ($model->getTable() == 'crm_users') {
            return UserManagers::withoutGlobalScopes()
                ->where('iam_user_id', UserHelper::currentUser()->id)
                ->where('iam_account_id', UserHelper::currentAccount()->id)
                ->where('crm_user_id', $model->id)
                ->exists();
        }

        if ($model->getTable() == 'crm_campaign_targets') {
            $campaign = Campaigns::withoutGlobalScopes()
                ->where('id', $model->crm_campaign_id)
                ->first();

            return $campaign && $campaign->iam_account_id == UserHelper::currentAccount()->id;
        }

        if ($model->getTable() == 'crm_target_users') {
            return $this->isTargetOwnedByMe($model->crm_target_id);
        }

        return $this->isOwnedByMe($model);
    }

    /**
     * Whether this role grants the given operation (create, update, delete, ...) on the model's table.
     */
    private function isOperationAllowed(Model $model, string $operation): bool
    {
        return in_array($model->getTable() . ':' . $operation, $this->allowedOperations());
    }

    /**
     * A record is owned by the sales person when it belongs to the current account and to the user, both before
     * and after the change, so it can not be moved into or out of the account or handed to someone else. This
     * matches apply(), which only lets a sales person read the records they own.
     */
    private function isOwnedByMe(Model $model): bool
    {
        $table = $model->getTable();

        if (! DatabaseHelper::isColumnExists($table, 'iam_account_id')) {
            return false;
        }

        $accountId = UserHelper::currentAccount()->id;

        if (
            $model->getAttribute('iam_account_id') != $accountId ||
            $model->getOriginal('iam_account_id', $accountId) != $accountId
        ) {
            return false;
        }

        if (! DatabaseHelper::isColumnExists($table, 'iam_user_id')) {
            return true;
        }

        $userId = UserHelper::me()->id;

        return $model->getAttribute('iam_user_id') == $userId &&
            $model->getOriginal('iam_user_id', $userId) == $userId;
    }

    private function isTargetOwnedByMe($targetId): bool
    {
        $target = Targets::withoutGlobalScopes()
            ->where('id', $targetId)
            ->first();

        return $target !== null && $this->isOwnedByMe($target);
    }

    public function getModule()
    {
        return 'crm';
    }

    public function allowedOperations() :array
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
            'crm_account_perspectives:read',
            'crm_account_users_perspective:read',
            'crm_ideal_customer_profiles:read',
            'crm_ideal_customer_profiles:create',
            'crm_ideal_customer_profiles:update',
            'crm_ideal_customer_profiles:delete',
            'crm_opportunities:read',
            'crm_opportunities:create',
            'crm_opportunities:update',
            'crm_user_managers:read',
            'crm_user_managers:create',
            'crm_user_managers:update',
            'crm_users_perspective:read',
            'crm_quotes:read',
            'crm_quotes:create',
            'crm_quotes:update',
            'crm_quote_items:read',
            'crm_quote_items:create',
            'crm_quote_items:update',
            'crm_quote_items:delete',
            'crm_opportunities:read',
            'crm_opportunities:create',
            'crm_opportunities:update',
            'crm_calls:read',
            'crm_calls:create',
            'crm_calls:update',
            'crm_calls:delete',
            'crm_emails:read',
            'crm_emails:create',
            'crm_emails:update',
            'crm_emails:delete',
            'crm_tasks:read',
            'crm_tasks:create',
            'crm_tasks:update',
            'crm_tasks:delete',
            'crm_notes:read',
            'crm_notes:create',
            'crm_notes:update',
            'crm_notes:delete',
            'crm_meetings:read',
            'crm_meetings:create',
            'crm_meetings:update',
            'crm_meetings:delete',

            'crm_campaigns:read',
            // Mailing analytics (stats, per-email, replies) - see CampaignAnalyticsService
            'crm_campaign_analytics:read',
            'crm_campaigns:create',
            'crm_campaigns:update',
            'crm_campaigns:delete',
            'crm_targets:read',
            'crm_targets:create',
            'crm_targets:update',
            'crm_targets:delete',
            'crm_target_users:read',
            'crm_target_users:create',
            'crm_target_users:update',
            'crm_target_users:delete',
            'crm_campaign_targets:read',
            'crm_campaign_targets:create',
            'crm_campaign_targets:update',
            'crm_campaign_targets:delete',
            'crm_email_templates:read',
            'crm_email_templates:create',
            'crm_email_templates:update',
            'crm_email_templates:delete',

            'crm_accounts_perspective:read',
            'crm_opportunities_performance:read',
            'crm_sales_people_perspective:read',

            // Customer statistics page and the Sources/Automations panels (round 2 UI test): a sales person got 403
            // on these. crm_account_managers_performance is left out on purpose: it shows other people's numbers.
            'crm_importers:read',
            'crm_monthly_new_accounts_performance:read',
            'crm_monthly_paying_customers_performance:read',
            'crm_weekly_new_accounts_performance:read',
            'crm_stats_performances_perspective:read',
        ];
    }

    public function canBeApplied($column)
    {
        if(self::DB_PREFIX === '*') {
            return true;
        }

        if(Str::startsWith($column, self::DB_PREFIX)) {
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
