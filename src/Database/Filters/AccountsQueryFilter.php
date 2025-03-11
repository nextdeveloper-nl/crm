<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
        

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class AccountsQueryFilter extends AbstractQueryFilter
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
    
    public function position($value)
    {
        return $this->builder->where('position', 'like', '%' . $value . '%');
    }
    
    public function additionalInformation($value)
    {
        return $this->builder->where('additional_information', 'like', '%' . $value . '%');
    }
    
    public function disablingReason($value)
    {
        return $this->builder->where('disabling_reason', 'like', '%' . $value . '%');
    }
    
    public function suspensionReason($value)
    {
        return $this->builder->where('suspension_reason', 'like', '%' . $value . '%');
    }

    public function riskLevel($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('risk_level', $operator, $value);
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

    public function isPayingCustomer($value)
    {
        if(!is_bool($value)) {
            $value = false;
        }

        return $this->builder->where('is_paying_customer', $value);
    }

    public function isSuspended($value)
    {
        if(!is_bool($value)) {
            $value = false;
        }

        return $this->builder->where('is_suspended', $value);
    }

    public function isServiceEnabled($value)
    {
        if(!is_bool($value)) {
            $value = false;
        }

        return $this->builder->where('is_service_enabled', $value);
    }

    public function isDisabled($value)
    {
        if(!is_bool($value)) {
            $value = false;
        }

        return $this->builder->where('is_disabled', $value);
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

    public function iamAccountId($value)
    {
            $iamAccount = \NextDeveloper\IAM\Database\Models\Accounts::where('uuid', $value)->first();

        if($iamAccount) {
            return $this->builder->where('iam_account_id', '=', $iamAccount->id);
        }
    }

    public function commonCityId($value)
    {
            $commonCity = \NextDeveloper\Commons\Database\Models\Cities::where('uuid', $value)->first();

        if($commonCity) {
            return $this->builder->where('common_city_id', '=', $commonCity->id);
        }
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n





















































}
