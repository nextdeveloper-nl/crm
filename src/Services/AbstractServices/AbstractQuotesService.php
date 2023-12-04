<?php

namespace NextDeveloper\CRM\Services\AbstractServices;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use NextDeveloper\IAM\Helpers\UserHelper;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Commons\Helpers\DatabaseHelper;
use NextDeveloper\CRM\Database\Models\Quotes;
use NextDeveloper\CRM\Database\Filters\QuotesQueryFilter;
use NextDeveloper\Commons\Exceptions\ModelNotFoundException;
use NextDeveloper\CRM\Events\Quotes\QuotesCreatedEvent;
use NextDeveloper\CRM\Events\Quotes\QuotesCreatingEvent;
use NextDeveloper\CRM\Events\Quotes\QuotesUpdatedEvent;
use NextDeveloper\CRM\Events\Quotes\QuotesUpdatingEvent;
use NextDeveloper\CRM\Events\Quotes\QuotesDeletedEvent;
use NextDeveloper\CRM\Events\Quotes\QuotesDeletingEvent;

/**
 * This class is responsible from managing the data for Quotes
 *
 * Class QuotesService.
 *
 * @package NextDeveloper\CRM\Database\Models
 */
class AbstractQuotesService
{
    public static function get(QuotesQueryFilter $filter = null, array $params = []) : Collection|LengthAwarePaginator
    {
        $enablePaginate = array_key_exists('paginate', $params);

        /**
        * Here we are adding null request since if filter is null, this means that this function is called from
        * non http application. This is actually not I think its a correct way to handle this problem but it's a workaround.
        *
        * Please let me know if you have any other idea about this; baris.bulut@nextdeveloper.com
        */
        if($filter == null) {
            $filter = new QuotesQueryFilter(new Request());
        }

        $perPage = config('commons.pagination.per_page');

        if($perPage == null) {
            $perPage = 20;
        }

        if(array_key_exists('per_page', $params)) {
            $perPage = intval($params['per_page']);

            if($perPage == 0) {
                $perPage = 20;
            }
        }

        if(array_key_exists('orderBy', $params)) {
            $filter->orderBy($params['orderBy']);
        }

        $model = Quotes::filter($filter);

        if($model && $enablePaginate) {
            return $model->paginate($perPage);
        } else {
            return $model->get();
        }
    }

    public static function getAll()
    {
        return Quotes::all();
    }

    /**
     * This method returns the model by looking at reference id
     *
     * @param  $ref
     * @return mixed
     */
    public static function getByRef($ref) : ?Quotes
    {
        return Quotes::findByRef($ref);
    }

    /**
     * This method returns the model by lookint at its id
     *
     * @param  $id
     * @return Quotes|null
     */
    public static function getById($id) : ?Quotes
    {
        return Quotes::where('id', $id)->first();
    }

    /**
     * This method returns the sub objects of the related models
     *
     * @param  $uuid
     * @param  $object
     * @return void
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public static function relatedObjects($uuid, $object)
    {
        try {
            $obj = Quotes::where('uuid', $uuid)->first();

            if(!$obj) {
                throw new ModelNotFoundException('Cannot find the related model');
            }

            if($obj) {
                return $obj->$object;
            }
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * This method created the model from an array.
     *
     * Throws an exception if stuck with any problem.
     *
     * @param  array $data
     * @return mixed
     * @throw  Exception
     */
    public static function create(array $data)
    {
        event(new QuotesCreatingEvent());

        if (array_key_exists('iam_accounts_id', $data)) {
            $data['iam_accounts_id'] = DatabaseHelper::uuidToId(
                '\NextDeveloper\IAM\Database\Models\Accounts',
                $data['iam_accounts_id']
            );
        }
        if (array_key_exists('crm_projects_id', $data)) {
            $data['crm_projects_id'] = DatabaseHelper::uuidToId(
                '\NextDeveloper\CRM\Database\Models\Projects',
                $data['crm_projects_id']
            );
        }
        if (array_key_exists('crm_opportunities_id', $data)) {
            $data['crm_opportunities_id'] = DatabaseHelper::uuidToId(
                '\NextDeveloper\CRM\Database\Models\Opportunities',
                $data['crm_opportunities_id']
            );
        }
    
        try {
            $model = Quotes::create($data);
        } catch(\Exception $e) {
            throw $e;
        }

        event(new QuotesCreatedEvent($model));

        return $model->fresh();
    }

    /**
     This function expects the ID inside the object.
    
     @param  array $data
     @return Quotes
     */
    public static function updateRaw(array $data) : ?Quotes
    {
        if(array_key_exists('id', $data)) {
            return self::update($data['id'], $data);
        }

        return null;
    }

    /**
     * This method updated the model from an array.
     *
     * Throws an exception if stuck with any problem.
     *
     * @param
     * @param  array $data
     * @return mixed
     * @throw  Exception
     */
    public static function update($id, array $data)
    {
        $model = Quotes::where('uuid', $id)->first();

        if (array_key_exists('iam_accounts_id', $data)) {
            $data['iam_accounts_id'] = DatabaseHelper::uuidToId(
                '\NextDeveloper\IAM\Database\Models\Accounts',
                $data['iam_accounts_id']
            );
        }
        if (array_key_exists('crm_projects_id', $data)) {
            $data['crm_projects_id'] = DatabaseHelper::uuidToId(
                '\NextDeveloper\CRM\Database\Models\Projects',
                $data['crm_projects_id']
            );
        }
        if (array_key_exists('crm_opportunities_id', $data)) {
            $data['crm_opportunities_id'] = DatabaseHelper::uuidToId(
                '\NextDeveloper\CRM\Database\Models\Opportunities',
                $data['crm_opportunities_id']
            );
        }
    
        event(new QuotesUpdatingEvent($model));

        try {
            $isUpdated = $model->update($data);
            $model = $model->fresh();
        } catch(\Exception $e) {
            throw $e;
        }

        event(new QuotesUpdatedEvent($model));

        return $model->fresh();
    }

    /**
     * This method updated the model from an array.
     *
     * Throws an exception if stuck with any problem.
     *
     * @param
     * @param  array $data
     * @return mixed
     * @throw  Exception
     */
    public static function delete($id)
    {
        $model = Quotes::where('uuid', $id)->first();

        event(new QuotesDeletingEvent());

        try {
            $model = $model->delete();
        } catch(\Exception $e) {
            throw $e;
        }

        return $model;
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
