<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
            

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class QuotesQueryFilter extends AbstractQueryFilter
{
    /**
     * @var Builder
     */
    protected $builder;
    
    public function name($value)
    {
        return $this->builder->where('name', 'like', '%' . $value . '%');
    }
    
    public function description($value)
    {
        return $this->builder->where('description', 'like', '%' . $value . '%');
    }
    
    public function detailedAmount($value)
    {
        return $this->builder->where('detailed_amount', 'like', '%' . $value . '%');
    }
    
    public function suggestedCurrencyCode($value)
    {
        return $this->builder->where('suggested_currency_code', 'like', '%' . $value . '%');
    }

    public function amount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('amount', $operator, $value);
    }
    
    public function suggestedPrice($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('suggested_price', $operator, $value);
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

    public function iamAccountsId($value)
    {
        $iamAccounts = \NextDeveloper\IAM\Database\Models\Accounts::where('uuid', $value)->first();

        if($iamAccounts) {
            return $this->builder->where('iam_accounts_id', '=', $iamAccounts->id);
        }
    }

    public function crmProjectsId($value)
    {
        $crmProjects = \NextDeveloper\CRM\Database\Models\Projects::where('uuid', $value)->first();

        if($crmProjects) {
            return $this->builder->where('crm_projects_id', '=', $crmProjects->id);
        }
    }

    public function crmOpportunitiesId($value)
    {
        $crmOpportunities = \NextDeveloper\CRM\Database\Models\Opportunities::where('uuid', $value)->first();

        if($crmOpportunities) {
            return $this->builder->where('crm_opportunities_id', '=', $crmOpportunities->id);
        }
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n
}