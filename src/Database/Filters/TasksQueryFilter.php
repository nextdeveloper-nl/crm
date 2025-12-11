<?php

namespace NextDeveloper\CRM\Database\Filters;

use App\Helpers\ObjectHelper;
use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
            

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class TasksQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;
    
    public function description($value)
    {
        return $this->builder->where('description', 'ilike', '%' . $value . '%');
    }

        
    public function name($value)
    {
        return $this->builder->where('name', 'ilike', '%' . $value . '%');
    }

        
    public function objectType($value)
    {
        return $this->builder->where('object_type', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of objectType
    public function object_type($value)
    {
        return $this->objectType($value);
    }
    
    public function priority($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('priority', $operator, $value);
    }

    
    public function isFinished($value)
    {
        return $this->builder->where('is_finished', $value);
    }

        //  This is an alias function of isFinished
    public function is_finished($value)
    {
        return $this->isFinished($value);
    }
     
    public function isDelayed($value)
    {
        return $this->builder->where('is_delayed', $value);
    }

        //  This is an alias function of isDelayed
    public function is_delayed($value)
    {
        return $this->isDelayed($value);
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

    public function dueDateStart($date)
    {
        return $this->builder->where('due_date', '>=', $date);
    }

    public function dueDateEnd($date)
    {
        return $this->builder->where('due_date', '<=', $date);
    }

    //  This is an alias function of dueDate
    public function due_date_start($value)
    {
        return $this->dueDateStart($value);
    }

    //  This is an alias function of dueDate
    public function due_date_end($value)
    {
        return $this->dueDateEnd($value);
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
    
    public function iamAccountId($value)
    {
            $iamAccount = \NextDeveloper\IAM\Database\Models\Accounts::where('uuid', $value)->first();

        if($iamAccount) {
            return $this->builder->where('iam_account_id', '=', $iamAccount->id);
        }
    }

    
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE









}
