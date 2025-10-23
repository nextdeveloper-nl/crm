<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
                

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class AccountsSummarizedPerspectiveQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;
    
    public function name($value)
    {
        return $this->builder->where('name', 'ilike', '%' . $value . '%');
    }

        
    public function accountOwnersFullname($value)
    {
        return $this->builder->where('account_owners_fullname', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of accountOwnersFullname
    public function account_owners_fullname($value)
    {
        return $this->accountOwnersFullname($value);
    }
        
    public function accountOwnersEmail($value)
    {
        return $this->builder->where('account_owners_email', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of accountOwnersEmail
    public function account_owners_email($value)
    {
        return $this->accountOwnersEmail($value);
    }
        
    public function accountOwnersPhoneNumber($value)
    {
        return $this->builder->where('account_owners_phone_number', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of accountOwnersPhoneNumber
    public function account_owners_phone_number($value)
    {
        return $this->accountOwnersPhoneNumber($value);
    }
        
    public function domainName($value)
    {
        return $this->builder->where('domain_name', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of domainName
    public function domain_name($value)
    {
        return $this->domainName($value);
    }
        
    public function countryName($value)
    {
        return $this->builder->where('country_name', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of countryName
    public function country_name($value)
    {
        return $this->countryName($value);
    }
        
    public function accountType($value)
    {
        return $this->builder->where('account_type', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of accountType
    public function account_type($value)
    {
        return $this->accountType($value);
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
