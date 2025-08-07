<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
                    

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class MeetingsQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;
    
    public function meetingNote($value)
    {
        return $this->builder->where('meeting_note', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of meetingNote
    public function meeting_note($value)
    {
        return $this->meetingNote($value);
    }
        
    public function outcome($value)
    {
        return $this->builder->where('outcome', 'ilike', '%' . $value . '%');
    }

        
    public function customerRequirements($value)
    {
        return $this->builder->where('customer_requirements', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of customerRequirements
    public function customer_requirements($value)
    {
        return $this->customerRequirements($value);
    }
        
    public function suggestions($value)
    {
        return $this->builder->where('suggestions', 'ilike', '%' . $value . '%');
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

    public function agendaCalendarItemId($value)
    {
            $agendaCalendarItem = \NextDeveloper\Agenda\Database\Models\CalendarItems::where('uuid', $value)->first();

        if($agendaCalendarItem) {
            return $this->builder->where('agenda_calendar_item_id', '=', $agendaCalendarItem->id);
        }
    }

        //  This is an alias function of agendaCalendarItem
    public function agenda_calendar_item_id($value)
    {
        return $this->agendaCalendarItem($value);
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

        //  This is an alias function of crmAccount
    public function crm_account_id($value)
    {
        return $this->crmAccount($value);
    }
    
    public function crmOpportunityId($value)
    {
            $crmOpportunity = \NextDeveloper\CRM\Database\Models\Opportunities::where('uuid', $value)->first();

        if($crmOpportunity) {
            return $this->builder->where('crm_opportunity_id', '=', $crmOpportunity->id);
        }
    }

        //  This is an alias function of crmOpportunity
    public function crm_opportunity_id($value)
    {
        return $this->crmOpportunity($value);
    }
    
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE























































}
