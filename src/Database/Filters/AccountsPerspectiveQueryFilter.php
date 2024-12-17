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

        //  This is an alias function of accountOwnersFullname
    public function account_owners_fullname($value)
    {
        return $this->accountOwnersFullname($value);
    }
        
    public function accountOwnersEmail($value)
    {
        return $this->builder->where('account_owners_email', 'like', '%' . $value . '%');
    }

        //  This is an alias function of accountOwnersEmail
    public function account_owners_email($value)
    {
        return $this->accountOwnersEmail($value);
    }
        
    public function accountOwnersPhoneNumber($value)
    {
        return $this->builder->where('account_owners_phone_number', 'like', '%' . $value . '%');
    }

        //  This is an alias function of accountOwnersPhoneNumber
    public function account_owners_phone_number($value)
    {
        return $this->accountOwnersPhoneNumber($value);
    }
        
    public function domainName($value)
    {
        return $this->builder->where('domain_name', 'like', '%' . $value . '%');
    }

        //  This is an alias function of domainName
    public function domain_name($value)
    {
        return $this->domainName($value);
    }
        
    public function countryName($value)
    {
        return $this->builder->where('country_name', 'like', '%' . $value . '%');
    }

        //  This is an alias function of countryName
    public function country_name($value)
    {
        return $this->countryName($value);
    }
        
    public function phoneNumber($value)
    {
        return $this->builder->where('phone_number', 'like', '%' . $value . '%');
    }

        //  This is an alias function of phoneNumber
    public function phone_number($value)
    {
        return $this->phoneNumber($value);
    }
        
    public function description($value)
    {
        return $this->builder->where('description', 'like', '%' . $value . '%');
    }

        
    public function accountType($value)
    {
        return $this->builder->where('account_type', 'like', '%' . $value . '%');
    }

        //  This is an alias function of accountType
    public function account_type($value)
    {
        return $this->accountType($value);
    }
        
    public function position($value)
    {
        return $this->builder->where('position', 'like', '%' . $value . '%');
    }

        
    public function headquarterCity($value)
    {
        return $this->builder->where('headquarter_city', 'like', '%' . $value . '%');
    }

        //  This is an alias function of headquarterCity
    public function headquarter_city($value)
    {
        return $this->headquarterCity($value);
    }
        
    public function additionalInformation($value)
    {
        return $this->builder->where('additional_information', 'like', '%' . $value . '%');
    }

        //  This is an alias function of additionalInformation
    public function additional_information($value)
    {
        return $this->additionalInformation($value);
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

        //  This is an alias function of riskLevel
    public function risk_level($value)
    {
        return $this->riskLevel($value);
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

        //  This is an alias function of totalNumberOfPersonel
    public function total_number_of_personel($value)
    {
        return $this->totalNumberOfPersonel($value);
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

        //  This is an alias function of employeeCount
    public function employee_count($value)
    {
        return $this->employeeCount($value);
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

        //  This is an alias function of productionPeopleCount
    public function production_people_count($value)
    {
        return $this->productionPeopleCount($value);
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

        //  This is an alias function of salesPeopleCount
    public function sales_people_count($value)
    {
        return $this->salesPeopleCount($value);
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

        //  This is an alias function of marketingPeopleCount
    public function marketing_people_count($value)
    {
        return $this->marketingPeopleCount($value);
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

        //  This is an alias function of supportPeopleCount
    public function support_people_count($value)
    {
        return $this->supportPeopleCount($value);
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

        //  This is an alias function of automationCount
    public function automation_count($value)
    {
        return $this->automationCount($value);
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

        //  This is an alias function of totalUserCount
    public function total_user_count($value)
    {
        return $this->totalUserCount($value);
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

        //  This is an alias function of registeredUserCount
    public function registered_user_count($value)
    {
        return $this->registeredUserCount($value);
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

        //  This is an alias function of idealCustomerProfileCount
    public function ideal_customer_profile_count($value)
    {
        return $this->idealCustomerProfileCount($value);
    }
    
    public function isPayingCustomer($value)
    {
        return $this->builder->where('is_paying_customer', $value);
    }

        //  This is an alias function of isPayingCustomer
    public function is_paying_customer($value)
    {
        return $this->isPayingCustomer($value);
    }
     
    public function isStartup($value)
    {
        return $this->builder->where('is_startup', $value);
    }

        //  This is an alias function of isStartup
    public function is_startup($value)
    {
        return $this->isStartup($value);
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

    public function commonDomainId($value)
    {
            $commonDomain = \NextDeveloper\Commons\Database\Models\Domains::where('uuid', $value)->first();

        if($commonDomain) {
            return $this->builder->where('common_domain_id', '=', $commonDomain->id);
        }
    }

        //  This is an alias function of commonDomain
    public function common_domain_id($value)
    {
        return $this->commonDomain($value);
    }
    
    public function commonCountryId($value)
    {
            $commonCountry = \NextDeveloper\Commons\Database\Models\Countries::where('uuid', $value)->first();

        if($commonCountry) {
            return $this->builder->where('common_country_id', '=', $commonCountry->id);
        }
    }

        //  This is an alias function of commonCountry
    public function common_country_id($value)
    {
        return $this->commonCountry($value);
    }
    
    public function iamAccountTypeId($value)
    {
            $iamAccountType = \NextDeveloper\IAM\Database\Models\AccountTypes::where('uuid', $value)->first();

        if($iamAccountType) {
            return $this->builder->where('iam_account_type_id', '=', $iamAccountType->id);
        }
    }

        //  This is an alias function of iamAccountType
    public function iam_account_type_id($value)
    {
        return $this->iamAccountType($value);
    }
    
    public function commonCityId($value)
    {
            $commonCity = \NextDeveloper\Commons\Database\Models\Cities::where('uuid', $value)->first();

        if($commonCity) {
            return $this->builder->where('common_city_id', '=', $commonCity->id);
        }
    }

        //  This is an alias function of commonCity
    public function common_city_id($value)
    {
        return $this->commonCity($value);
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
