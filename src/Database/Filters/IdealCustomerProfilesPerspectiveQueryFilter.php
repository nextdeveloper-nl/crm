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

        //  This is an alias function of companySize
    public function company_size($value)
    {
        return $this->companySize($value);
    }
        
    public function additionalNotes($value)
    {
        return $this->builder->where('additional_notes', 'like', '%' . $value . '%');
    }

        //  This is an alias function of additionalNotes
    public function additional_notes($value)
    {
        return $this->additionalNotes($value);
    }
        
    public function growthStage($value)
    {
        return $this->builder->where('growth_stage', 'like', '%' . $value . '%');
    }

        //  This is an alias function of growthStage
    public function growth_stage($value)
    {
        return $this->growthStage($value);
    }
        
    public function businessModel($value)
    {
        return $this->builder->where('business_model', 'like', '%' . $value . '%');
    }

        //  This is an alias function of businessModel
    public function business_model($value)
    {
        return $this->businessModel($value);
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

        //  This is an alias function of technologyRank
    public function technology_rank($value)
    {
        return $this->technologyRank($value);
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

        //  This is an alias function of opportunityCount
    public function opportunity_count($value)
    {
        return $this->opportunityCount($value);
    }
    
    public function isWorkingHomeOffice($value)
    {
        return $this->builder->where('is_working_home_office', $value);
    }

        //  This is an alias function of isWorkingHomeOffice
    public function is_working_home_office($value)
    {
        return $this->isWorkingHomeOffice($value);
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

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE













}
