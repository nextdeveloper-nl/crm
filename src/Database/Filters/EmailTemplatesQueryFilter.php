<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
                

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class EmailTemplatesQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;
    
    public function subject($value)
    {
        return $this->builder->where('subject', 'ilike', '%' . $value . '%');
    }

        
    public function content($value)
    {
        return $this->builder->where('content', 'ilike', '%' . $value . '%');
    }

        
    public function emailMeta($value)
    {
        return $this->builder->where('email_meta', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of emailMeta
    public function email_meta($value)
    {
        return $this->emailMeta($value);
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

    public function iamUserId($value)
    {
            $iamUser = \NextDeveloper\IAM\Database\Models\Users::where('uuid', $value)->first();

        if($iamUser) {
            return $this->builder->where('iam_user_id', '=', $iamUser->id);
        }
    }

    
    public function iamAccountId($value)
    {
            $iamAccount = \NextDeveloper\IAM\Database\Models\Accounts::where('uuid', $value)->first();

        if($iamAccount) {
            return $this->builder->where('iam_account_id', '=', $iamAccount->id);
        }
    }

    
    public function crmCampaignId($value)
    {
            $crmCampaign = \NextDeveloper\CRM\Database\Models\Campaigns::where('uuid', $value)->first();

        if($crmCampaign) {
            return $this->builder->where('crm_campaign_id', '=', $crmCampaign->id);
        }
    }

        //  This is an alias function of crmCampaign
    public function crm_campaign_id($value)
    {
        return $this->crmCampaign($value);
    }
    
    public function communicationChannelId($value)
    {
            $communicationChannel = \NextDeveloper\Communication\Database\Models\Channels::where('uuid', $value)->first();

        if($communicationChannel) {
            return $this->builder->where('communication_channel_id', '=', $communicationChannel->id);
        }
    }

        //  This is an alias function of communicationChannel
    public function communication_channel_id($value)
    {
        return $this->communicationChannel($value);
    }
    
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE












}
