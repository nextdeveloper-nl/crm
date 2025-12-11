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
    
    public function type($value)
    {
        return $this->builder->where('type', 'ilike', '%' . $value . '%');
    }

    
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
    
    public function prospectCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('prospect_count', $operator, $value);
    }

        //  This is an alias function of prospectCount
    public function prospect_count($value)
    {
        return $this->prospectCount($value);
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
    
    public function researchCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('research_count', $operator, $value);
    }

        //  This is an alias function of researchCount
    public function research_count($value)
    {
        return $this->researchCount($value);
    }
    
    public function needAnalysisCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('need_analysis_count', $operator, $value);
    }

        //  This is an alias function of needAnalysisCount
    public function need_analysis_count($value)
    {
        return $this->needAnalysisCount($value);
    }
    
    public function approachCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('approach_count', $operator, $value);
    }

        //  This is an alias function of approachCount
    public function approach_count($value)
    {
        return $this->approachCount($value);
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
    
    public function identifyingDecisionMakersCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('identifying_decision_makers_count', $operator, $value);
    }

        //  This is an alias function of identifyingDecisionMakersCount
    public function identifying_decision_makers_count($value)
    {
        return $this->identifyingDecisionMakersCount($value);
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
    
    public function negotiationCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('negotiation_count', $operator, $value);
    }

        //  This is an alias function of negotiationCount
    public function negotiation_count($value)
    {
        return $this->negotiationCount($value);
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
    
    public function lostCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('lost_count', $operator, $value);
    }

        //  This is an alias function of lostCount
    public function lost_count($value)
    {
        return $this->lostCount($value);
    }
    
    public function cancelledCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('cancelled_count', $operator, $value);
    }

        //  This is an alias function of cancelledCount
    public function cancelled_count($value)
    {
        return $this->cancelledCount($value);
    }
    
    public function perceptionAnalysisCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('perception_analysis_count', $operator, $value);
    }

        //  This is an alias function of perceptionAnalysisCount
    public function perception_analysis_count($value)
    {
        return $this->perceptionAnalysisCount($value);
    }
    
    public function renewalCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('renewal_count', $operator, $value);
    }

        //  This is an alias function of renewalCount
    public function renewal_count($value)
    {
        return $this->renewalCount($value);
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
