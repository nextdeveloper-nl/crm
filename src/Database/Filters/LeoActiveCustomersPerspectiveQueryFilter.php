<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;


/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class LeoActiveCustomersPerspectiveQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;
    
    public function name($value)
    {
        return $this->builder->where('name', 'ilike', '%' . $value . '%');
    }

        
    public function description($value)
    {
        return $this->builder->where('description', 'ilike', '%' . $value . '%');
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
        
    public function accountOwner($value)
    {
        return $this->builder->where('account_owner', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of accountOwner
    public function account_owner($value)
    {
        return $this->accountOwner($value);
    }
        
    public function accountOwnerEmail($value)
    {
        return $this->builder->where('account_owner_email', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of accountOwnerEmail
    public function account_owner_email($value)
    {
        return $this->accountOwnerEmail($value);
    }
        
    public function currencyCode($value)
    {
        return $this->builder->where('currency_code', 'ilike', '%' . $value . '%');
    }

        //  This is an alias function of currencyCode
    public function currency_code($value)
    {
        return $this->currencyCode($value);
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
    
    public function accountingAccount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('accounting_account', $operator, $value);
    }

        //  This is an alias function of accountingAccount
    public function accounting_account($value)
    {
        return $this->accountingAccount($value);
    }
    
    public function crmAccount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('crm_account', $operator, $value);
    }

        //  This is an alias function of crmAccount
    public function crm_account($value)
    {
        return $this->crmAccount($value);
    }
    
    public function iaasAccount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('iaas_account', $operator, $value);
    }

        //  This is an alias function of iaasAccount
    public function iaas_account($value)
    {
        return $this->iaasAccount($value);
    }
    
    public function marketplaceAccount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('marketplace_account', $operator, $value);
    }

        //  This is an alias function of marketplaceAccount
    public function marketplace_account($value)
    {
        return $this->marketplaceAccount($value);
    }
    
    public function partnershipAccount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('partnership_account', $operator, $value);
    }

        //  This is an alias function of partnershipAccount
    public function partnership_account($value)
    {
        return $this->partnershipAccount($value);
    }
    
    public function ipaasAccount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('ipaas_account', $operator, $value);
    }

        //  This is an alias function of ipaasAccount
    public function ipaas_account($value)
    {
        return $this->ipaasAccount($value);
    }
    
    public function intelligenceAccount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('intelligence_account', $operator, $value);
    }

        //  This is an alias function of intelligenceAccount
    public function intelligence_account($value)
    {
        return $this->intelligenceAccount($value);
    }
    
    public function autoquillAccount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('autoquill_account', $operator, $value);
    }

        //  This is an alias function of autoquillAccount
    public function autoquill_account($value)
    {
        return $this->autoquillAccount($value);
    }
    
    public function llmoceanAccount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('llmocean_account', $operator, $value);
    }

        //  This is an alias function of llmoceanAccount
    public function llmocean_account($value)
    {
        return $this->llmoceanAccount($value);
    }
    
    public function unpaidInvoiceCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('unpaid_invoice_count', $operator, $value);
    }

        //  This is an alias function of unpaidInvoiceCount
    public function unpaid_invoice_count($value)
    {
        return $this->unpaidInvoiceCount($value);
    }
    
    public function virtualMachineCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('virtual_machine_count', $operator, $value);
    }

        //  This is an alias function of virtualMachineCount
    public function virtual_machine_count($value)
    {
        return $this->virtualMachineCount($value);
    }
    
    public function virtualDiskImageCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('virtual_disk_image_count', $operator, $value);
    }

        //  This is an alias function of virtualDiskImageCount
    public function virtual_disk_image_count($value)
    {
        return $this->virtualDiskImageCount($value);
    }
    
    public function virtualNetworkCardCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('virtual_network_card_count', $operator, $value);
    }

        //  This is an alias function of virtualNetworkCardCount
    public function virtual_network_card_count($value)
    {
        return $this->virtualNetworkCardCount($value);
    }
    
    public function ipAddressCount($value)
    {
        $operator = substr($value, 0, 1);

        if ($operator != '<' || $operator != '>') {
            $operator = '=';
        } else {
            $value = substr($value, 1);
        }

        return $this->builder->where('ip_address_count', $operator, $value);
    }

        //  This is an alias function of ipAddressCount
    public function ip_address_count($value)
    {
        return $this->ipAddressCount($value);
    }
    
    public function isActive($value)
    {
        return $this->builder->where('is_active', $value);
    }

        //  This is an alias function of isActive
    public function is_active($value)
    {
        return $this->isActive($value);
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
     
    public function isCrmSuspended($value)
    {
        return $this->builder->where('is_crm_suspended', $value);
    }

        //  This is an alias function of isCrmSuspended
    public function is_crm_suspended($value)
    {
        return $this->isCrmSuspended($value);
    }
     
    public function isCrmDisabled($value)
    {
        return $this->builder->where('is_crm_disabled', $value);
    }

        //  This is an alias function of isCrmDisabled
    public function is_crm_disabled($value)
    {
        return $this->isCrmDisabled($value);
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

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}
