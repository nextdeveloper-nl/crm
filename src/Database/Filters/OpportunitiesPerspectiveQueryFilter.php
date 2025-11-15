<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
                

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class OpportunitiesPerspectiveQueryFilter extends AbstractQueryFilter
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
    
    public function name($value)
    {
        return $this->builder->where('name', 'ilike', '%' . $value . '%');
    }

        
    public function description($value)
    {
        return $this->builder->where('description', 'ilike', '%' . $value . '%');
    }

        
    public function source($value)
    {
        return $this->builder->where('source', 'ilike', '%' . $value . '%');
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
        
    public function responsibleName($value)
    {
        return $this->builder->where('responsible_name', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of responsibleName
    public function responsible_name($value)
    {
        return $this->responsibleName($value);
    }
    
    public function probability($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('probability', $operator, $value);
    }

    
    public function quoteCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('quote_count', $operator, $value);
    }

        //  This is an alias function of quoteCount
    public function quote_count($value)
    {
        return $this->quoteCount($value);
    }
    
    public function meetingCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('meeting_count', $operator, $value);
    }

        //  This is an alias function of meetingCount
    public function meeting_count($value)
    {
        return $this->meetingCount($value);
    }
    
    public function callCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('call_count', $operator, $value);
    }

        //  This is an alias function of callCount
    public function call_count($value)
    {
        return $this->callCount($value);
    }
    
    public function projectCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('project_count', $operator, $value);
    }

        //  This is an alias function of projectCount
    public function project_count($value)
    {
        return $this->projectCount($value);
    }
    
    public function deadlineStart($date)
    {
        return $this->builder->where('deadline', '>=', $date);
    }

    public function deadlineEnd($date)
    {
        return $this->builder->where('deadline', '<=', $date);
    }

    //  This is an alias function of deadline
    public function deadline_start($value)
    {
        return $this->deadlineStart($value);
    }

    //  This is an alias function of deadline
    public function deadline_end($value)
    {
        return $this->deadlineEnd($value);
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

    
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE


































}
