<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
                    

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class UsersPerspectiveQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;
    
    public function name($value)
    {
        return $this->builder->where('name', 'like', '%' . $value . '%');
    }
    
    public function surname($value)
    {
        return $this->builder->where('surname', 'like', '%' . $value . '%');
    }
    
    public function fullname($value)
    {
        return $this->builder->where('fullname', 'like', '%' . $value . '%');
    }
    
    public function email($value)
    {
        return $this->builder->where('email', 'like', '%' . $value . '%');
    }
    
    public function about($value)
    {
        return $this->builder->where('about', 'like', '%' . $value . '%');
    }
    
    public function pronoun($value)
    {
        return $this->builder->where('pronoun', 'like', '%' . $value . '%');
    }
    
    public function nin($value)
    {
        return $this->builder->where('nin', 'like', '%' . $value . '%');
    }
    
    public function country($value)
    {
        return $this->builder->where('country', 'like', '%' . $value . '%');
    }
    
    public function language($value)
    {
        return $this->builder->where('language', 'like', '%' . $value . '%');
    }
    
    public function phoneNumber($value)
    {
        return $this->builder->where('phone_number', 'like', '%' . $value . '%');
    }
    
    public function position($value)
    {
        return $this->builder->where('position', 'like', '%' . $value . '%');
    }
    
    public function job($value)
    {
        return $this->builder->where('job', 'like', '%' . $value . '%');
    }
    
    public function jobDescription($value)
    {
        return $this->builder->where('job_description', 'like', '%' . $value . '%');
    }
    
    public function hobbies($value)
    {
        return $this->builder->where('hobbies', 'like', '%' . $value . '%');
    }
    
    public function city($value)
    {
        return $this->builder->where('city', 'like', '%' . $value . '%');
    }
    
    public function relationshipStatus($value)
    {
        return $this->builder->where('relationship_status', 'like', '%' . $value . '%');
    }

    public function childCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('child_count', $operator, $value);
    }

    public function isRegistered($value)
    {
        if(!is_bool($value)) {
            $value = false;
        }

        return $this->builder->where('is_registered', $value);
    }

    public function isNinVerified($value)
    {
        if(!is_bool($value)) {
            $value = false;
        }

        return $this->builder->where('is_nin_verified', $value);
    }

    public function isEmailVerified($value)
    {
        if(!is_bool($value)) {
            $value = false;
        }

        return $this->builder->where('is_email_verified', $value);
    }

    public function isPhoneNumberVerified($value)
    {
        if(!is_bool($value)) {
            $value = false;
        }

        return $this->builder->where('is_phone_number_verified', $value);
    }

    public function isEvangelist($value)
    {
        if(!is_bool($value)) {
            $value = false;
        }

        return $this->builder->where('is_evangelist', $value);
    }

    public function isSingle($value)
    {
        if(!is_bool($value)) {
            $value = false;
        }

        return $this->builder->where('is_single', $value);
    }

    public function birthdayStart($date)
    {
        return $this->builder->where('birthday', '>=', $date);
    }

    public function birthdayEnd($date)
    {
        return $this->builder->where('birthday', '<=', $date);
    }

    public function iamUpdatedAtStart($date)
    {
        return $this->builder->where('iam_updated_at', '>=', $date);
    }

    public function iamUpdatedAtEnd($date)
    {
        return $this->builder->where('iam_updated_at', '<=', $date);
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

    public function commonCountryId($value)
    {
            $commonCountry = \NextDeveloper\Commons\Database\Models\Countries::where('uuid', $value)->first();

        if($commonCountry) {
            return $this->builder->where('common_country_id', '=', $commonCountry->id);
        }
    }

    public function commonLanguageId($value)
    {
            $commonLanguage = \NextDeveloper\Commons\Database\Models\Languages::where('uuid', $value)->first();

        if($commonLanguage) {
            return $this->builder->where('common_language_id', '=', $commonLanguage->id);
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

    public function crmAccountId($value)
    {
            $crmAccount = \NextDeveloper\CRM\Database\Models\Accounts::where('uuid', $value)->first();

        if($crmAccount) {
            return $this->builder->where('crm_account_id', '=', $crmAccount->id);
        }
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE








}
