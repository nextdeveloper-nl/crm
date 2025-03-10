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
    
    public function accountOwnersFullname($value)
    {
        return $this->builder->where('account_owners_fullname', 'like', '%' . $value . '%');
    }
    
    public function accountOwnersEmail($value)
    {
        return $this->builder->where('account_owners_email', 'like', '%' . $value . '%');
    }
    
    public function accountOwnersPhoneNumber($value)
    {
        return $this->builder->where('account_owners_phone_number', 'like', '%' . $value . '%');
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

    public function totalUserCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('total_user_count', $operator, $value);
    }

    public function registeredUserCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('registered_user_count', $operator, $value);
    }

    public function idealCustomerProfileCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('ideal_customer_profile_count', $operator, $value);
    }

    public function isPayingCustomer($value)
    {
        if(!is_bool($value)) {
            $value = false;
        }

        return $this->builder->where('is_paying_customer', $value);
    }

    public function isDisabled($value)
    {
        if(!is_bool($value)) {
            $value = false;
        }

        return $this->builder->where('is_disabled', $value);
    }

    public function isSuspended($value)
    {
        if(!is_bool($value)) {
            $value = false;
        }

        return $this->builder->where('is_suspended', $value);
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
