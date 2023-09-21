<?php

namespace NextDeveloper\CRM\Http\Controllers\AccountManagers;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Generator\Http\Traits\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\AccountManagers\AccountManagersUpdateRequest;
use NextDeveloper\CRM\Database\Filters\AccountManagersQueryFilter;
use NextDeveloper\CRM\Services\AccountManagersService;
use NextDeveloper\CRM\Http\Requests\AccountManagers\AccountManagersCreateRequest;

class AccountManagersController extends AbstractController
{
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
     * This method created AccountManagers object on database.
     *
     * @param  AccountManagersCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(AccountManagersCreateRequest $request)
    {
        $model = AccountManagersService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates AccountManagers object on database.
     *
     * @param  $accountManagersId
     * @param  CountryCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($accountManagersId, AccountManagersUpdateRequest $request)
    {
        $model = AccountManagersService::update($accountManagersId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates AccountManagers object on database.
     *
     * @param  $accountManagersId
     * @param  CountryCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($accountManagersId)
    {
        $model = AccountManagersService::delete($accountManagersId);

        return ResponsableFactory::makeResponse($this, $model);
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}