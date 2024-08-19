<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
    

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class IdealCustomerProfilesQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;
    
    public function customerPositions($value)
    {
        return $this->builder->where('customer_positions', 'like', '%' . $value . '%');
    }
    
    public function companySize($value)
    {
        return $this->builder->where('company_size', 'like', '%' . $value . '%');
    }
    
    public function solutionsInterestedIn($value)
    {
        return $this->builder->where('solutions_interested_in', 'like', '%' . $value . '%');
    }
    
    public function currentTechnologyStack($value)
    {
        return $this->builder->where('current_technology_stack', 'like', '%' . $value . '%');
    }
    
    public function decisionMakingProcess($value)
    {
        return $this->builder->where('decision_making_process', 'like', '%' . $value . '%');
    }
    
    public function implementationTimeline($value)
    {
        return $this->builder->where('implementation_timeline', 'like', '%' . $value . '%');
    }
    
    public function uniqueSellingProposition($value)
    {
        return $this->builder->where('unique_selling_proposition', 'like', '%' . $value . '%');
    }
    
    public function leadGenerationChannels($value)
    {
        return $this->builder->where('lead_generation_channels', 'like', '%' . $value . '%');
    }
    
    public function salesProcess($value)
    {
        return $this->builder->where('sales_process', 'like', '%' . $value . '%');
    }
    
    public function salesFunnel($value)
    {
        return $this->builder->where('sales_funnel', 'like', '%' . $value . '%');
    }
    
    public function additionalNotes($value)
    {
        return $this->builder->where('additional_notes', 'like', '%' . $value . '%');
    }

    public function isWorkingHomeOffice($value)
    {
        if(!is_bool($value)) {
            $value = false;
        }

        return $this->builder->where('is_working_home_office', $value);
    }

    public function createdAtStart($date)
    {
        return $this->builder->where('created_at', '>=', $date);
    }

    public function createdAtEnd($date)
    {
        return $this->builder->where('created_at', '<=', $date);
    }

    public function updatedAtStart($date)
    {
        return $this->builder->where('updated_at', '>=', $date);
    }

    public function updatedAtEnd($date)
    {
        return $this->builder->where('updated_at', '<=', $date);
    }

    public function deletedAtStart($date)
    {
        return $this->builder->where('deleted_at', '>=', $date);
    }

    public function deletedAtEnd($date)
    {
        return $this->builder->where('deleted_at', '<=', $date);
    }

    public function crmAccountId($value)
    {
            $crmAccount = \NextDeveloper\CRM\Database\Models\Accounts::where('uuid', $value)->first();

        if($crmAccount) {
            return $this->builder->where('crm_account_id', '=', $crmAccount->id);
        }
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE









}
