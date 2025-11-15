<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
        

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class CampaignTargetsQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;

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
    
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

















}
