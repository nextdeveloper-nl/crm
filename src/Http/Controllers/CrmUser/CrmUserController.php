<?php

namespace NextDeveloper\CRM\Http\Controllers\CrmUser;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Generator\Http\Traits\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\CrmUser\CrmUserUpdateRequest;
use NextDeveloper\CRM\Database\Filters\CrmUserQueryFilter;
use NextDeveloper\CRM\Services\CrmUserService;
use NextDeveloper\CRM\Http\Requests\CrmUser\CrmUserCreateRequest;

class CrmUserController extends AbstractController
{
    /**
    * This method returns the list of crmusers.
    *
    * optional http params:
    * - paginate: If you set paginate parameter, the result will be returned paginated.
    *
    * @param CrmUserQueryFilter $filter An object that builds search query
    * @param Request $request Laravel request object, this holds all data about request. Automatically populated.
    * @return \Illuminate\Http\JsonResponse
    */
    public function index(CrmUserQueryFilter $filter, Request $request) {
        $data = CrmUserService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
    * This method receives ID for the related model and returns the item to the client.
    *
    * @param $crmUserId
    * @return mixed|null
    * @throws \Laravel\Octane\Exceptions\DdException
    */
    public function show($ref) {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = CrmUserService::getByRef($ref);

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
    * This method created CrmUser object on database.
    *
    * @param CrmUserCreateRequest $request
    * @return mixed|null
    * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
    */
    public function store(CrmUserCreateRequest $request) {
        $model = CrmUserService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
    * This method updates CrmUser object on database.
    *
    * @param $crmUserId
    * @param CountryCreateRequest $request
    * @return mixed|null
    * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
    */
    public function update($crmUserId, CrmUserUpdateRequest $request) {
        $model = CrmUserService::update($crmUserId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
    * This method updates CrmUser object on database.
    *
    * @param $crmUserId
    * @param CountryCreateRequest $request
    * @return mixed|null
    * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
    */
    public function destroy($crmUserId) {
        $model = CrmUserService::delete($crmUserId);

        return ResponsableFactory::makeResponse($this, $model);
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}