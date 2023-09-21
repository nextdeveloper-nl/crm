<?php

namespace NextDeveloper\CRM\Services\AbstractServices;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use NextDeveloper\IAM\Helpers\UserHelper;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Commons\Helpers\DatabaseHelper;
use NextDeveloper\CRM\Database\Models\AccountManagers;
use NextDeveloper\CRM\Database\Filters\AccountManagersQueryFilter;
use NextDeveloper\CRM\Events\AccountManagers\AccountManagersCreatedEvent;
use NextDeveloper\CRM\Events\AccountManagers\AccountManagersCreatingEvent;
use NextDeveloper\CRM\Events\AccountManagers\AccountManagersUpdatedEvent;
use NextDeveloper\CRM\Events\AccountManagers\AccountManagersUpdatingEvent;
use NextDeveloper\CRM\Events\AccountManagers\AccountManagersDeletedEvent;
use NextDeveloper\CRM\Events\AccountManagers\AccountManagersDeletingEvent;


/**
 * This class is responsible from managing the data for AccountManagers
 *
 * Class AccountManagersService.
 *
 * @package NextDeveloper\CRM\Database\Models
 */
class AbstractAccountManagersService
{
    public static function get(AccountManagersQueryFilter $filter = null, array $params = []) : Collection|LengthAwarePaginator
    {
        $enablePaginate = array_key_exists('paginate', $params);

        /**
        * Here we are adding null request since if filter is null, this means that this function is called from
        * non http application. This is actually not I think its a correct way to handle this problem but it's a workaround.
        *
        * Please let me know if you have any other idea about this; baris.bulut@nextdeveloper.com
        */
        if($filter == null) {
            $filter = new AccountManagersQueryFilter(new Request());
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

        $model = AccountManagers::filter($filter);

        if($model && $enablePaginate) {
            return $model->paginate($perPage);
        } else {
            return $model->get();
        }
    }

    public static function getAll()
    {
        return AccountManagers::all();
    }

    /**
     * This method returns the model by looking at reference id
     *
     * @param  $ref
     * @return mixed
     */
    public static function getByRef($ref) : ?AccountManagers
    {
        return AccountManagers::findByRef($ref);
    }

    /**
     * This method returns the model by lookint at its id
     *
     * @param  $id
     * @return AccountManagers|null
     */
    public static function getById($id) : ?AccountManagers
    {
        return AccountManagers::where('id', $id)->first();
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
        event(new AccountManagersCreatingEvent());

        if (array_key_exists('crm_account_id', $data)) {
            $data['crm_account_id'] = DatabaseHelper::uuidToId(
                '\NextDeveloper\CRM\Database\Models\Accounts',
                $data['crm_account_id']
            );
        }
        if (array_key_exists('iam_user_id', $data)) {
            $data['iam_user_id'] = DatabaseHelper::uuidToId(
                '\NextDeveloper\IAM\Database\Models\Users',
                $data['iam_user_id']
            );
        }
            
        try {
            $model = AccountManagers::create($data);
        } catch(\Exception $e) {
            throw $e;
        }

        event(new AccountManagersCreatedEvent($model));

        return $model->fresh();
    }

    /**
     This function expects the ID inside the object.
    
     @param  array $data
     @return AccountManagers
     */
    public static function updateRaw(array $data) : ?AccountManagers
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
        $model = AccountManagers::where('uuid', $id)->first();

        if (array_key_exists('crm_account_id', $data)) {
            $data['crm_account_id'] = DatabaseHelper::uuidToId(
                '\NextDeveloper\CRM\Database\Models\Accounts',
                $data['crm_account_id']
            );
        }
        if (array_key_exists('iam_user_id', $data)) {
            $data['iam_user_id'] = DatabaseHelper::uuidToId(
                '\NextDeveloper\IAM\Database\Models\Users',
                $data['iam_user_id']
            );
        }
    
        event(new AccountManagersUpdatingEvent($model));

        try {
            $isUpdated = $model->update($data);
            $model = $model->fresh();
        } catch(\Exception $e) {
            throw $e;
        }

        event(new AccountManagersUpdatedEvent($model));

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
    public static function delete($id, array $data)
    {
        $model = AccountManagers::where('uuid', $id)->first();

        event(new AccountManagersDeletingEvent());

        try {
            $model = $model->delete();
        } catch(\Exception $e) {
            throw $e;
        }

        event(new AccountManagersDeletedEvent($model));

        return $model;
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
