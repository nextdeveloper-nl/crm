<?php

namespace NextDeveloper\CRM\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * Row filter for the campaign perspectives (the Customer Lists and Customers tabs of a campaign, leo4#1391).
 *
 * Their iam_* columns do not say who may see a row: in crm_campaign_target_users_perspective iam_account_id is the
 * customer's own account and there is no iam_user_id, so the generic "iam_user_id = me" filter of the roles does
 * not apply at all. A row is shown only when its campaign is a campaign of the current account, and, for roles
 * that only see their own campaigns, one created by the current user.
 */
class CampaignPerspectiveHelper
{
    public const TABLES = [
        'crm_campaign_targets_perspective',
        'crm_campaign_target_users_perspective',
    ];

    public static function isCampaignPerspective(Model $model): bool
    {
        return in_array($model->getTable(), self::TABLES, true);
    }

    /**
     * Limits the perspective to campaigns of the current account, and to the current user's campaigns when
     * $isOwnCampaignsOnly is true (the same rows crm_campaigns shows that role).
     */
    public static function apply(Builder $builder, Model $model, bool $isOwnCampaignsOnly): void
    {
        //  Plain query builder: the campaigns subquery must not pick up the model scopes (LimitScope caps at 20).
        $campaigns = DB::table('crm_campaigns')
            ->select('id')
            ->where('iam_account_id', UserHelper::currentAccount()->id)
            ->whereNull('deleted_at');

        if ($isOwnCampaignsOnly) {
            $campaigns->where('iam_user_id', UserHelper::me()->id);
        }

        $builder->whereIn($model->getTable().'.crm_campaign_id', $campaigns);
    }
}
