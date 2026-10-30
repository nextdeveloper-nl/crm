<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;


/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class AccountCommunicationsPerspectiveQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;

    public function direction($value)
    {
        return $this->builder->where('direction', '=', $value);
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

    public function recipient($value)
    {
        return $this->builder->where('recipient', 'ilike', '%' . $value . '%');
    }


    public function subject($value)
    {
        return $this->builder->where('subject', 'ilike', '%' . $value . '%');
    }


    public function body($value)
    {
        return $this->builder->where('body', 'ilike', '%' . $value . '%');
    }


    public function contentType($value)
    {
        return $this->builder->where('content_type', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of contentType
    public function content_type($value)
    {
        return $this->contentType($value);
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

    public function messageStatus($value)
    {
        return $this->builder->where('message_status', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of messageStatus
    public function message_status($value)
    {
        return $this->messageStatus($value);
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

    public function crmAccountId($value)
    {
            $crmAccount = \NextDeveloper\CRM\Database\Models\Accounts::where('uuid', $value)->first();

        if($crmAccount) {
            return $this->builder->where('crm_account_id', '=', $crmAccount->id);
        }
    }

        //  This is an alias function of crmAccountId
    public function crm_account_id($value)
    {
        return $this->crmAccountId($value);
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
