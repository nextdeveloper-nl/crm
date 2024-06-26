<?php

namespace NextDeveloper\CRM\Http\Controllers\AccountManagers;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\AccountManagers\AccountManagersUpdateRequest;
use NextDeveloper\CRM\Database\Filters\AccountManagersQueryFilter;
use NextDeveloper\CRM\Database\Models\AccountManagers;
use NextDeveloper\CRM\Services\AccountManagersService;
use NextDeveloper\CRM\Http\Requests\AccountManagers\AccountManagersCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags;use NextDeveloper\Commons\Http\Traits\Addresses;
class AccountManagersController extends AbstractController
{
    private $model = AccountManagers::class;

    use Tags;
    use Addresses;
    /**
     * This method returns the list of accountmanagers.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  AccountManagersQueryFilter $filter  An object that builds search query
     * @param  Request                    $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(AccountManagersQueryFilter $filter, Request $request)
    {
        $data = AccountManagersService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = AccountManagersService::getActions();

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * Makes the related action to the object
     *
     * @param  $objectId
     * @param  $action
     * @return array
     */
    public function doAction($objectId, $action)
    {
        $actionId = AccountManagersService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $accountManagersId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = AccountManagersService::getByRef($ref);

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method returns the list of sub objects the related object. Sub object means an object which is preowned by
     * this object.
     *
     * It can be tags, addresses, states etc.
     *
     * @param  $ref
     * @param  $subObject
     * @return void
     */
    public function relatedObjects($ref, $subObject)
    {
        $objects = AccountManagersService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created AccountManagers object on database.
     *
     * @param  AccountManagersCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(AccountManagersCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = AccountManagersService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates AccountManagers object on database.
     *
     * @param  $accountManagersId
     * @param  AccountManagersUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($accountManagersId, AccountManagersUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = AccountManagersService::update($accountManagersId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates AccountManagers object on database.
     *
     * @param  $accountManagersId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($accountManagersId)
    {
        $model = AccountManagersService::delete($accountManagersId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
