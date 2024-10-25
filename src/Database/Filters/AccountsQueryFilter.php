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
        return $this->builder->where('position', 'like', '%' . $value . '%');
    }

        
    public function headquarterCity($value)
    {
        return $this->builder->where('headquarter_city', 'like', '%' . $value . '%');
    }

        //  This is an alias function of headquarterCity
    public function headquarter_city($value)
    {
        return $this->headquarterCity($value);
    }
        
    public function additionalInformation($value)
    {
        return $this->builder->where('additional_information', 'like', '%' . $value . '%');
    }

        //  This is an alias function of additionalInformation
    public function additional_information($value)
    {
        return $this->additionalInformation($value);
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
    
    public function companySize($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('company_size', $operator, $value);
    }

        //  This is an alias function of companySize
    public function company_size($value)
    {
        return $this->companySize($value);
    }
    
    public function employeeCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('employee_count', $operator, $value);
    }

        //  This is an alias function of employeeCount
    public function employee_count($value)
    {
        return $this->employeeCount($value);
    }
    
    public function productionPeopleCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('production_people_count', $operator, $value);
    }

        //  This is an alias function of productionPeopleCount
    public function production_people_count($value)
    {
        return $this->productionPeopleCount($value);
    }
    
    public function salesPeopleCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('sales_people_count', $operator, $value);
    }

        //  This is an alias function of salesPeopleCount
    public function sales_people_count($value)
    {
        return $this->salesPeopleCount($value);
    }
    
    public function marketingPeopleCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('marketing_people_count', $operator, $value);
    }

        //  This is an alias function of marketingPeopleCount
    public function marketing_people_count($value)
    {
        return $this->marketingPeopleCount($value);
    }
    
    public function supportPeopleCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('support_people_count', $operator, $value);
    }

        //  This is an alias function of supportPeopleCount
    public function support_people_count($value)
    {
        return $this->supportPeopleCount($value);
    }
    
    public function automationCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('automation_count', $operator, $value);
    }

        //  This is an alias function of automationCount
    public function automation_count($value)
    {
        return $this->automationCount($value);
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
     
    public function isStartup($value)
    {
        return $this->builder->where('is_startup', $value);
    }

        //  This is an alias function of isStartup
    public function is_startup($value)
    {
        return $this->isStartup($value);
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
