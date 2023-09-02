<?php

namespace NextDeveloper\CRM\Services\AbstractServices;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use NextDeveloper\IAM\Helpers\UserHelper;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Commons\Helpers\DatabaseHelper;
use NextDeveloper\CRM\Database\Models\CrmAccount;
use NextDeveloper\CRM\Database\Filters\CrmAccountQueryFilter;
use NextDeveloper\CRM\Events\CrmAccount\CrmAccountCreatedEvent;
use NextDeveloper\CRM\Events\CrmAccount\CrmAccountCreatingEvent;
use NextDeveloper\CRM\Events\CrmAccount\CrmAccountUpdatedEvent;
use NextDeveloper\CRM\Events\CrmAccount\CrmAccountUpdatingEvent;
use NextDeveloper\CRM\Events\CrmAccount\CrmAccountDeletedEvent;
use NextDeveloper\CRM\Events\CrmAccount\CrmAccountDeletingEvent;


/**
* This class is responsible from managing the data for CrmAccount
*
* Class CrmAccountService.
*
* @package NextDeveloper\CRM\Database\Models
*/
class AbstractCrmAccountService {
    public static function get(CrmAccountQueryFilter $filter = null, array $params = []) : Collection|LengthAwarePaginator {
        $enablePaginate = array_key_exists('paginate', $params);

        /**
        * Here we are adding null request since if filter is null, this means that this function is called from
        * non http application. This is actually not I think its a correct way to handle this problem but it's a workaround.
        *
        * Please let me know if you have any other idea about this; baris.bulut@nextdeveloper.com
        */
        if($filter == null)
            $filter = new CrmAccountQueryFilter(new Request());

        $perPage = config('commons.pagination.per_page');

        if($perPage == null)
            $perPage = 20;

        if(array_key_exists('per_page', $params)) {
            $perPage = intval($params['per_page']);

            if($perPage == 0)
                $perPage = 20;
        }

        if(array_key_exists('orderBy', $params)) {
            $filter->orderBy($params['orderBy']);
        }

        $model = CrmAccount::filter($filter);

        if($model && $enablePaginate)
            return $model->paginate($perPage);
        else
            return $model->get();
    }

    public static function getAll() {
        return CrmAccount::all();
    }

    /**
    * This method returns the model by looking at reference id
    *
    * @param $ref
    * @return mixed
    */
    public static function getByRef($ref) : ?CrmAccount {
        return CrmAccount::findByRef($ref);
    }

    /**
    * This method returns the model by lookint at its id
    *
    * @param $id
    * @return CrmAccount|null
    */
    public static function getById($id) : ?CrmAccount {
        return CrmAccount::where('id', $id)->first();
    }

    /**
    * This method created the model from an array.
    *
    * Throws an exception if stuck with any problem.
    *
    * @param array $data
    * @return mixed
    * @throw Exception
    */
    public static function create(array $data) {
        event( new CrmAccountCreatingEvent() );

                if (array_key_exists('iam_account_id', $data))
            $data['iam_account_id'] = DatabaseHelper::uuidToId(
                '\NextDeveloper\IAM\Database\Models\IamAccount',
                $data['iam_account_id']
            );
	        
        try {
            $model = CrmAccount::create($data);
        } catch(\Exception $e) {
            throw $e;
        }

        event( new CrmAccountCreatedEvent($model) );

        return $model->fresh();
    }

/**
* This function expects the ID inside the object.
*
* @param array $data
* @return CrmAccount
*/
public static function updateRaw(array $data) : ?CrmAccount
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
    * @param array $data
    * @return mixed
    * @throw Exception
    */
    public static function update($id, array $data) {
        $model = CrmAccount::where('uuid', $id)->first();

                if (array_key_exists('iam_account_id', $data))
            $data['iam_account_id'] = DatabaseHelper::uuidToId(
                '\NextDeveloper\IAM\Database\Models\IamAccount',
                $data['iam_account_id']
            );
	
        event( new CrmAccountUpdatingEvent($model) );

        try {
           $isUpdated = $model->update($data);
           $model = $model->fresh();
        } catch(\Exception $e) {
           throw $e;
        }

        event( new CrmAccountUpdatedEvent($model) );

        return $model->fresh();
    }

    /**
    * This method updated the model from an array.
    *
    * Throws an exception if stuck with any problem.
    *
    * @param
    * @param array $data
    * @return mixed
    * @throw Exception
    */
    public static function delete($id, array $data) {
        $model = CrmAccount::where('uuid', $id)->first();

        event( new CrmAccountDeletingEvent() );

        try {
            $model = $model->delete();
        } catch(\Exception $e) {
            throw $e;
        }

        event( new CrmAccountDeletedEvent($model) );

        return $model;
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
