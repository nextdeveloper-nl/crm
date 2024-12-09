<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;


/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class CallsQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;

    public function name($value)
    {
        return $this->builder->where('name', 'ilike', '%' . $value . '%');
    }


    public function description($value)
    {
        return $this->builder->where('description', 'ilike', '%' . $value . '%');
    }


    public function disposition($value)
    {
        return $this->builder->where('disposition', 'ilike', '%' . $value . '%');
    }


    public function fromNumber($value)
    {
        return $this->builder->where('from_number', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of fromNumber
    public function from_number($value)
    {
        return $this->fromNumber($value);
    }

    public function toNumber($value)
    {
        return $this->builder->where('to_number', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of toNumber
    public function to_number($value)
    {
        return $this->toNumber($value);
    }

    public function callDirection($value)
    {
        return $this->builder->where('call_direction', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of callDirection
    public function call_direction($value)
    {
        return $this->callDirection($value);
    }

    public function iamAccountIt($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('iam_account_it', $operator, $value);
    }

        //  This is an alias function of iamAccountIt
    public function iam_account_it($value)
    {
        return $this->iamAccountIt($value);
    }

    public function duration($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('duration', $operator, $value);
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


    public function crmAccountId($value)
    {
            $crmAccount = \NextDeveloper\CRM\Database\Models\Accounts::where('uuid', $value)->first();

        if($crmAccount) {
            return $this->builder->where('crm_account_id', '=', $crmAccount->id);
        }
    }

        //  This is an alias function of crmAccount
    public function crm_account_id($value)
    {
        return $this->crmAccount($value);
    }

    public function crmOpportunityId($value)
    {
            $crmOpportunity = \NextDeveloper\CRM\Database\Models\Opportunities::where('uuid', $value)->first();

        if($crmOpportunity) {
            return $this->builder->where('crm_opportunity_id', '=', $crmOpportunity->id);
        }
    }

        //  This is an alias function of crmOpportunity
    public function crm_opportunity_id($value)
    {
        return $this->crmOpportunity($value);
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

































}
