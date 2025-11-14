<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
        

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class AccountsQueryFilter extends AbstractQueryFilter
{
    /**
     * Filter by tags
     *
     * @param  $values
     * @return Builder
     */
    public function tags($values)
    {
        $tags = explode(',', $values);

        $search = '';

        for($i = 0; $i < count($tags); $i++) {
            $search .= "'" . trim($tags[$i]) . "',";
        }

        $search = substr($search, 0, -1);

        return $this->builder->whereRaw('tags @> ARRAY[' . $search . ']');
    }

    /**
     * @var Builder
     */
    protected $builder;
    
    public function position($value)
    {
        return $this->builder->where('position', 'ilike', '%' . $value . '%');
    }

        
    public function additionalInformation($value)
    {
        return $this->builder->where('additional_information', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of additionalInformation
    public function additional_information($value)
    {
        return $this->additionalInformation($value);
    }
        
    public function disablingReason($value)
    {
        return $this->builder->where('disabling_reason', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of disablingReason
    public function disabling_reason($value)
    {
        return $this->disablingReason($value);
    }
        
    public function suspensionReason($value)
    {
        return $this->builder->where('suspension_reason', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of suspensionReason
    public function suspension_reason($value)
    {
        return $this->suspensionReason($value);
    }
    
    public function riskLevel($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('risk_level', $operator, $value);
    }

        //  This is an alias function of riskLevel
    public function risk_level($value)
    {
        return $this->riskLevel($value);
    }
    
    public function technologyRank($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('technology_rank', $operator, $value);
    }

        //  This is an alias function of technologyRank
    public function technology_rank($value)
    {
        return $this->technologyRank($value);
    }
    
    public function isPayingCustomer($value)
    {
        return $this->builder->where('is_paying_customer', $value);
    }

        //  This is an alias function of isPayingCustomer
    public function is_paying_customer($value)
    {
        return $this->isPayingCustomer($value);
    }
     
    public function isSuspended($value)
    {
        return $this->builder->where('is_suspended', $value);
    }

        //  This is an alias function of isSuspended
    public function is_suspended($value)
    {
        return $this->isSuspended($value);
    }
     
    public function isServiceEnabled($value)
    {
        return $this->builder->where('is_service_enabled', $value);
    }

        //  This is an alias function of isServiceEnabled
    public function is_service_enabled($value)
    {
        return $this->isServiceEnabled($value);
    }
     
    public function isDisabled($value)
    {
        return $this->builder->where('is_disabled', $value);
    }

        //  This is an alias function of isDisabled
    public function is_disabled($value)
    {
        return $this->isDisabled($value);
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

    public function iamAccountId($value)
    {
            $iamAccount = \NextDeveloper\IAM\Database\Models\Accounts::where('uuid', $value)->first();

        if($iamAccount) {
            return $this->builder->where('iam_account_id', '=', $iamAccount->id);
        }
    }

    
    public function commonCityId($value)
    {
            $commonCity = \NextDeveloper\Commons\Database\Models\Cities::where('uuid', $value)->first();

        if($commonCity) {
            return $this->builder->where('common_city_id', '=', $commonCity->id);
        }
    }

        //  This is an alias function of commonCity
    public function common_city_id($value)
    {
        return $this->commonCity($value);
    }
    
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n






























































}
