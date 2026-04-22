<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;


/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class CampaignTargetUsersPerspectiveQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;

    public function campaignName($value)
    {
        return $this->builder->where('campaign_name', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of campaignName
    public function campaign_name($value)
    {
        return $this->campaignName($value);
    }

    public function campaignStatus($value)
    {
        return $this->builder->where('campaign_status', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of campaignStatus
    public function campaign_status($value)
    {
        return $this->campaignStatus($value);
    }

    public function targetName($value)
    {
        return $this->builder->where('target_name', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of targetName
    public function target_name($value)
    {
        return $this->targetName($value);
    }

    public function fullname($value)
    {
        return $this->builder->where('fullname', 'ilike', '%' . $value . '%');
    }


    public function email($value)
    {
        return $this->builder->where('email', 'ilike', '%' . $value . '%');
    }


    public function phoneNumber($value)
    {
        return $this->builder->where('phone_number', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of phoneNumber
    public function phone_number($value)
    {
        return $this->phoneNumber($value);
    }

    public function accountName($value)
    {
        return $this->builder->where('account_name', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of accountName
    public function account_name($value)
    {
        return $this->accountName($value);
    }

    public function responsibleAccount($value)
    {
        return $this->builder->where('responsible_account', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of responsibleAccount
    public function responsible_account($value)
    {
        return $this->responsibleAccount($value);
    }

    public function createdAtStart($date)
    {
        return $this->builder->where('created_at', '>=', $date);
    }

    public function createdAtEnd($date)
    {
        return $this->builder->where('created_at', '<=', $date);
    }

    //  This is an alias function of createdAt
    public function created_at_start($value)
    {
        return $this->createdAtStart($value);
    }

    //  This is an alias function of createdAt
    public function created_at_end($value)
    {
        return $this->createdAtEnd($value);
    }

    public function updatedAtStart($date)
    {
        return $this->builder->where('updated_at', '>=', $date);
    }

    public function updatedAtEnd($date)
    {
        return $this->builder->where('updated_at', '<=', $date);
    }

    //  This is an alias function of updatedAt
    public function updated_at_start($value)
    {
        return $this->updatedAtStart($value);
    }

    //  This is an alias function of updatedAt
    public function updated_at_end($value)
    {
        return $this->updatedAtEnd($value);
    }

    public function deletedAtStart($date)
    {
        return $this->builder->where('deleted_at', '>=', $date);
    }

    public function deletedAtEnd($date)
    {
        return $this->builder->where('deleted_at', '<=', $date);
    }

    //  This is an alias function of deletedAt
    public function deleted_at_start($value)
    {
        return $this->deletedAtStart($value);
    }

    //  This is an alias function of deletedAt
    public function deleted_at_end($value)
    {
        return $this->deletedAtEnd($value);
    }

    public function crmTargetId($value)
    {
            $crmTarget = \NextDeveloper\CRM\Database\Models\Targets::where('uuid', $value)->first();

        if($crmTarget) {
            return $this->builder->where('crm_target_id', '=', $crmTarget->id);
        }
    }

        //  This is an alias function of crmTarget
    public function crm_target_id($value)
    {
        return $this->crmTarget($value);
    }

    public function crmUserId($value)
    {
            $crmUser = \NextDeveloper\CRM\Database\Models\Users::where('uuid', $value)->first();

        if($crmUser) {
            return $this->builder->where('crm_user_id', '=', $crmUser->id);
        }
    }

        //  This is an alias function of crmUser
    public function crm_user_id($value)
    {
        return $this->crmUser($value);
    }

    public function iamAccountId($value)
    {
            $iamAccount = \NextDeveloper\IAM\Database\Models\Accounts::where('uuid', $value)->first();

        if($iamAccount) {
            return $this->builder->where('iam_account_id', '=', $iamAccount->id);
        }
    }


    public function responsibleAccountId($value)
    {
            $responsibleAccount = \NextDeveloper\IAM\Database\Models\Accounts::where('uuid', $value)->first();

        if($responsibleAccount) {
            return $this->builder->where('responsible_account_id', '=', $responsibleAccount->id);
        }
    }

        //  This is an alias function of responsibleAccount
    public function responsible_account_id($value)
    {
        return $this->responsibleAccount($value);
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE


}
