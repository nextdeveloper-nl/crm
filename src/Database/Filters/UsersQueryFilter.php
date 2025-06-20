<?php

namespace NextDeveloper\CRM\Database\Filters;

use Illuminate\Database\Eloquent\Builder;
use NextDeveloper\Commons\Database\Filters\AbstractQueryFilter;
use NextDeveloper\Accounts\Database\Models\User;


/**
 * This class automatically puts where clause on database so that use can filter
 * data returned from the query.
 */
class UsersQueryFilter extends AbstractQueryFilter
{
    /**
     * Filter by tags
     *
     * @param  $values
     * @return Builder
     */
    public function tags($values)
    {
        $tags = explode(',', $values);

        $search = '';

        for($i = 0; $i < count($tags); $i++) {
            $search .= "'" . trim($tags[$i]) . "',";
        }

        $search = substr($search, 0, -1);

        return $this->builder->whereRaw('tags @> ARRAY[' . $search . ']');
    }

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

    public function jobDescription($value)
    {
        return $this->builder->where('job_description', 'ilike', '%' . $value . '%');
    }

    public function hobbies($value)
    {
        return $this->builder->where('hobbies', 'ilike', '%' . $value . '%');
    }

    public function city($value)
    {
        return $this->builder->where('city', 'ilike', '%' . $value . '%');
    }

    public function relationshipStatus($value)
    {
        return $this->builder->where('relationship_status', 'ilike', '%' . $value . '%');
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

    public function iamUserId($value)
    {
            $iamUser = \NextDeveloper\IAM\Database\Models\Users::where('uuid', $value)->first();

        if($iamUser) {
            return $this->builder->where('iam_user_id', '=', $iamUser->id);
        }
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n



























































}
