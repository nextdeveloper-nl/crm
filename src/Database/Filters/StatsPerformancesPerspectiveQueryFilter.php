<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;


/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class StatsPerformancesPerspectiveQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;

    public function newAccounts($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('new_accounts', $operator, $value);
    }

        //  This is an alias function of newAccounts
    public function new_accounts($value)
    {
        return $this->newAccounts($value);
    }
    
    public function paidInvoices($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('paid_invoices', $operator, $value);
    }

        //  This is an alias function of paidInvoices
    public function paid_invoices($value)
    {
        return $this->paidInvoices($value);
    }
    
    public function activeCustomers($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('active_customers', $operator, $value);
    }

        //  This is an alias function of activeCustomers
    public function active_customers($value)
    {
        return $this->activeCustomers($value);
    }
    
    public function statDateStart($date)
    {
        return $this->builder->where('stat_date', '>=', $date);
    }

    public function statDateEnd($date)
    {
        return $this->builder->where('stat_date', '<=', $date);
    }

    //  This is an alias function of statDate
    public function stat_date_start($value)
    {
        return $this->statDateStart($value);
    }

    //  This is an alias function of statDate
    public function stat_date_end($value)
    {
        return $this->statDateEnd($value);
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
