<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
    

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class AccountManagersPerformanceQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;
    
    public function accountManager($value)
    {
        return $this->builder->where('account_manager', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of accountManager
    public function account_manager($value)
    {
        return $this->accountManager($value);
    }
    
    public function totalAccounts($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('total_accounts', $operator, $value);
    }

        //  This is an alias function of totalAccounts
    public function total_accounts($value)
    {
        return $this->totalAccounts($value);
    }
    
    public function creationDateStart($date)
    {
        return $this->builder->where('creation_date', '>=', $date);
    }

    public function creationDateEnd($date)
    {
        return $this->builder->where('creation_date', '<=', $date);
    }

    //  This is an alias function of creationDate
    public function creation_date_start($value)
    {
        return $this->creationDateStart($value);
    }

    //  This is an alias function of creationDate
    public function creation_date_end($value)
    {
        return $this->creationDateEnd($value);
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
