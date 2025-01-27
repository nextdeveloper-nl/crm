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

        //  This is an alias function of jobDescription
    public function job_description($value)
    {
        return $this->jobDescription($value);
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

        //  This is an alias function of relationshipStatus
    public function relationship_status($value)
    {
        return $this->relationshipStatus($value);
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

        //  This is an alias function of childCount
    public function child_count($value)
    {
        return $this->childCount($value);
    }
    
    public function isEvangelist($value)
    {
        return $this->builder->where('is_evangelist', $value);
    }

        //  This is an alias function of isEvangelist
    public function is_evangelist($value)
    {
        return $this->isEvangelist($value);
    }
     
    public function isSingle($value)
    {
        return $this->builder->where('is_single', $value);
    }

        //  This is an alias function of isSingle
    public function is_single($value)
    {
        return $this->isSingle($value);
    }
     
    public function birthdayStart($date)
    {
        return $this->builder->where('birthday', '>=', $date);
    }

    public function birthdayEnd($date)
    {
        return $this->builder->where('birthday', '<=', $date);
    }

    //  This is an alias function of birthday
    public function birthday_start($value)
    {
        return $this->birthdayStart($value);
    }

    //  This is an alias function of birthday
    public function birthday_end($value)
    {
        return $this->birthdayEnd($value);
    }

    public function iamUpdatedAtStart($date)
    {
        return $this->builder->where('iam_updated_at', '>=', $date);
    }

    public function iamUpdatedAtEnd($date)
    {
        return $this->builder->where('iam_updated_at', '<=', $date);
    }

    //  This is an alias function of iamUpdatedAt
    public function iam_updated_at_start($value)
    {
        return $this->iamUpdatedAtStart($value);
    }

    //  This is an alias function of iamUpdatedAt
    public function iam_updated_at_end($value)
    {
        return $this->iamUpdatedAtEnd($value);
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
    
    public function commonLanguageId($value)
    {
            $commonLanguage = \NextDeveloper\Commons\Database\Models\Languages::where('uuid', $value)->first();

        if($commonLanguage) {
            return $this->builder->where('common_language_id', '=', $commonLanguage->id);
        }
    }

        //  This is an alias function of commonLanguage
    public function common_language_id($value)
    {
        return $this->commonLanguage($value);
    }
    
    public function iamUserId($value)
    {
            $iamUser = \NextDeveloper\IAM\Database\Models\Users::where('uuid', $value)->first();

        if($iamUser) {
            return $this->builder->where('iam_user_id', '=', $iamUser->id);
        }
    }

    
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE










































}
