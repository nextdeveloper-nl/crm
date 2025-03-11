<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;


/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class IdealCustomerProfilesPerspectiveQueryFilter extends AbstractQueryFilter
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
    
    public function companySize($value)
    {
        return $this->builder->where('company_size', 'like', '%' . $value . '%');
    }
    
    public function additionalNotes($value)
    {
        return $this->builder->where('additional_notes', 'like', '%' . $value . '%');
    }
    
    public function growthStage($value)
    {
        return $this->builder->where('growth_stage', 'like', '%' . $value . '%');
    }
    
    public function businessModel($value)
    {
        return $this->builder->where('business_model', 'like', '%' . $value . '%');
    }

    public function technologyRank($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('technology_rank', $operator, $value);
    }

    public function opportunityCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('opportunity_count', $operator, $value);
    }

    public function isWorkingHomeOffice($value)
    {
        if(!is_bool($value)) {
            $value = false;
        }

        return $this->builder->where('is_working_home_office', $value);
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

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE














}
