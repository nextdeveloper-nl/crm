<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;


/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class DailyNewAccountsPerformanceQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;

    public function dayCode($value)
    {
        return $this->builder->where('day_code', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of dayCode
    public function day_code($value)
    {
        return $this->dayCode($value);
    }

    public function count($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('count', $operator, $value);
    }


    public function dayStartStart($date)
    {
        return $this->builder->where('day_start', '>=', $date);
    }

    public function dayStartEnd($date)
    {
        return $this->builder->where('day_start', '<=', $date);
    }

    //  This is an alias function of dayStart
    public function day_start_start($value)
    {
        return $this->dayStartStart($value);
    }

    //  This is an alias function of dayStart
    public function day_start_end($value)
    {
        return $this->dayStartEnd($value);
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
