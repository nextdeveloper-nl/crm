<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
    

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class OpportunitiesPerformanceQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;

    public function leadsCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('leads_count', $operator, $value);
    }

        //  This is an alias function of leadsCount
    public function leads_count($value)
    {
        return $this->leadsCount($value);
    }
    
    public function qualificationCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('qualification_count', $operator, $value);
    }

        //  This is an alias function of qualificationCount
    public function qualification_count($value)
    {
        return $this->qualificationCount($value);
    }
    
    public function valuePropositionCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('value_proposition_count', $operator, $value);
    }

        //  This is an alias function of valuePropositionCount
    public function value_proposition_count($value)
    {
        return $this->valuePropositionCount($value);
    }
    
    public function proposalCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('proposal_count', $operator, $value);
    }

        //  This is an alias function of proposalCount
    public function proposal_count($value)
    {
        return $this->proposalCount($value);
    }
    
    public function wonCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('won_count', $operator, $value);
    }

        //  This is an alias function of wonCount
    public function won_count($value)
    {
        return $this->wonCount($value);
    }
    
    public function iamAccountId($value)
    {
            $iamAccount = \NextDeveloper\IAM\Database\Models\Accounts::where('uuid', $value)->first();

        if($iamAccount) {
            return $this->builder->where('iam_account_id', '=', $iamAccount->id);
        }
    }

    
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
