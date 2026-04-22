<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
            

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class UserEmailsPerspectiveQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;
    
    public function position($value)
    {
        return $this->builder->where('position', 'ilike', '%' . $value . '%');
    }

        
    public function job($value)
    {
        return $this->builder->where('job', 'ilike', '%' . $value . '%');
    }

        
    public function fullname($value)
    {
        return $this->builder->where('fullname', 'ilike', '%' . $value . '%');
    }

        
    public function email($value)
    {
        return $this->builder->where('email', 'ilike', '%' . $value . '%');
    }

        
    public function phoneNumber($value)
    {
        return $this->builder->where('phone_number', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of phoneNumber
    public function phone_number($value)
    {
        return $this->phoneNumber($value);
    }
        
    public function fromEmailAddress($value)
    {
        return $this->builder->where('from_email_address', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of fromEmailAddress
    public function from_email_address($value)
    {
        return $this->fromEmailAddress($value);
    }
        
    public function subject($value)
    {
        return $this->builder->where('subject', 'ilike', '%' . $value . '%');
    }

        
    public function body($value)
    {
        return $this->builder->where('body', 'ilike', '%' . $value . '%');
    }

    
    public function isSuspended($value)
    {
        return $this->builder->where('is_suspended', $value);
    }

        //  This is an alias function of isSuspended
    public function is_suspended($value)
    {
        return $this->isSuspended($value);
    }
     
    public function isMarketingEmail($value)
    {
        return $this->builder->where('is_marketing_email', $value);
    }

        //  This is an alias function of isMarketingEmail
    public function is_marketing_email($value)
    {
        return $this->isMarketingEmail($value);
    }
     
    public function deliverAtStart($date)
    {
        return $this->builder->where('deliver_at', '>=', $date);
    }

    public function deliverAtEnd($date)
    {
        return $this->builder->where('deliver_at', '<=', $date);
    }

    //  This is an alias function of deliverAt
    public function deliver_at_start($value)
    {
        return $this->deliverAtStart($value);
    }

    //  This is an alias function of deliverAt
    public function deliver_at_end($value)
    {
        return $this->deliverAtEnd($value);
    }

    public function deliveredAtStart($date)
    {
        return $this->builder->where('delivered_at', '>=', $date);
    }

    public function deliveredAtEnd($date)
    {
        return $this->builder->where('delivered_at', '<=', $date);
    }

    //  This is an alias function of deliveredAt
    public function delivered_at_start($value)
    {
        return $this->deliveredAtStart($value);
    }

    //  This is an alias function of deliveredAt
    public function delivered_at_end($value)
    {
        return $this->deliveredAtEnd($value);
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

    public function iamUserId($value)
    {
            $iamUser = \NextDeveloper\IAM\Database\Models\Users::where('uuid', $value)->first();

        if($iamUser) {
            return $this->builder->where('iam_user_id', '=', $iamUser->id);
        }
    }

    
    public function communicationEmailId($value)
    {
            $communicationEmail = \NextDeveloper\Communication\Database\Models\Emails::where('uuid', $value)->first();

        if($communicationEmail) {
            return $this->builder->where('communication_email_id', '=', $communicationEmail->id);
        }
    }

        //  This is an alias function of communicationEmail
    public function communication_email_id($value)
    {
        return $this->communicationEmail($value);
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
