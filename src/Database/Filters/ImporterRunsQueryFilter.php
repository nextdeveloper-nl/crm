<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
            

/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class ImporterRunsQueryFilter extends AbstractQueryFilter
{

    /**
     * @var Builder
     */
    protected $builder;

    public function crmImporterId($value)
    {
        $obj = \NextDeveloper\CRM\Database\Models\Importers::where('uuid', $value)->first();

        if($obj) {
            return $this->builder->where('crm_importer_id', '=', $obj->id);
        }
    }

    //  Alias of crmImporterId
    public function crm_importer_id($value)
    {
        return $this->crmImporterId($value);
    }

    public function status($value)
    {
        return $this->builder->where('status', 'ilike', '%' . $value . '%');
    }

    public function payloadHash($value)
    {
        return $this->builder->where('payload_hash', 'ilike', '%' . $value . '%');
    }

    public function error($value)
    {
        return $this->builder->where('error', 'ilike', '%' . $value . '%');
    }

    public function objectType($value)
    {
        return $this->builder->where('object_type', 'ilike', '%' . $value . '%');
    }

    public function objectId($value)
    {
        return $this->builder->where('object_id', '=', $value);
    }

    public function importedAtStart($date)
    {
        return $this->builder->where('imported_at', '>=', $date);
    }

    public function importedAtEnd($date)
    {
        return $this->builder->where('imported_at', '<=', $date);
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


    public function iamAccountId($value)
    {
            $iamAccount = \NextDeveloper\IAM\Database\Models\Accounts::where('uuid', $value)->first();

        if($iamAccount) {
            return $this->builder->where('iam_account_id', '=', $iamAccount->id);
        }
    }


    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE















































































}
