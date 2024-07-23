<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
                        

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class AccountsPerspectiveQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;
    
    public function name($value)
    {
        return $this->builder->where('name', 'like', '%' . $value . '%');
    }
    
    public function domainName($value)
    {
        return $this->builder->where('domain_name', 'like', '%' . $value . '%');
    }
    
    public function countryName($value)
    {
        return $this->builder->where('country_name', 'like', '%' . $value . '%');
    }
    
    public function phoneNumber($value)
    {
        return $this->builder->where('phone_number', 'like', '%' . $value . '%');
    }
    
    public function description($value)
    {
        return $this->builder->where('description', 'like', '%' . $value . '%');
    }
    
    public function accountType($value)
    {
        return $this->builder->where('account_type', 'like', '%' . $value . '%');
    }
    
    public function position($value)
    {
        return $this->builder->where('position', 'like', '%' . $value . '%');
    }
    
    public function headquarterCity($value)
    {
        return $this->builder->where('headquarter_city', 'like', '%' . $value . '%');
    }
    
    public function additionalInformation($value)
    {
        return $this->builder->where('additional_information', 'like', '%' . $value . '%');
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

    public function totalNumberOfPersonel($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('total_number_of_personel', $operator, $value);
    }

    public function employeeCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('employee_count', $operator, $value);
    }

    public function productionPeopleCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('production_people_count', $operator, $value);
    }

    public function salesPeopleCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('sales_people_count', $operator, $value);
    }

    public function marketingPeopleCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('marketing_people_count', $operator, $value);
    }

    public function supportPeopleCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('support_people_count', $operator, $value);
    }

    public function automationCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('automation_count', $operator, $value);
    }

    public function isPayingCustomer($value)
    {
        if(!is_bool($value)) {
            $value = false;
        }

        return $this->builder->where('is_paying_customer', $value);
    }

    public function isStartup($value)
    {
        if(!is_bool($value)) {
            $value = false;
        }

        return $this->builder->where('is_startup', $value);
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

    public function iamAccountId($value)
    {
            $iamAccount = \NextDeveloper\IAM\Database\Models\Accounts::where('uuid', $value)->first();

        if($iamAccount) {
            return $this->builder->where('iam_account_id', '=', $iamAccount->id);
        }
    }

    public function commonDomainId($value)
    {
            $commonDomain = \NextDeveloper\Commons\Database\Models\Domains::where('uuid', $value)->first();

        if($commonDomain) {
            return $this->builder->where('common_domain_id', '=', $commonDomain->id);
        }
    }

    public function commonCountryId($value)
    {
            $commonCountry = \NextDeveloper\Commons\Database\Models\Countries::where('uuid', $value)->first();

        if($commonCountry) {
            return $this->builder->where('common_country_id', '=', $commonCountry->id);
        }
    }

    public function crmAccountId($value)
    {
            $crmAccount = \NextDeveloper\CRM\Database\Models\Accounts::where('uuid', $value)->first();

        if($crmAccount) {
            return $this->builder->where('crm_account_id', '=', $crmAccount->id);
        }
    }

    public function iamAccountTypeId($value)
    {
            $iamAccountType = \NextDeveloper\IAM\Database\Models\AccountTypes::where('uuid', $value)->first();

        if($iamAccountType) {
            return $this->builder->where('iam_account_type_id', '=', $iamAccountType->id);
        }
    }

    public function commonCityId($value)
    {
            $commonCity = \NextDeveloper\Commons\Database\Models\Cities::where('uuid', $value)->first();

        if($commonCity) {
            return $this->builder->where('common_city_id', '=', $commonCity->id);
        }
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE


}
