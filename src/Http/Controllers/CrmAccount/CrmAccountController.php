<?php

namespace NextDeveloper\CRM\Http\Controllers\CrmAccount;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Generator\Http\Traits\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\CrmAccount\CrmAccountUpdateRequest;
use NextDeveloper\CRM\Database\Filters\CrmAccountQueryFilter;
use NextDeveloper\CRM\Services\CrmAccountService;
use NextDeveloper\CRM\Http\Requests\CrmAccount\CrmAccountCreateRequest;

class CrmAccountController extends AbstractController
{
    /**
    * This method returns the list of crmaccounts.
    *
    * optional http params:
    * - paginate: If you set paginate parameter, the result will be returned paginated.
    *
    * @param CrmAccountQueryFilter $filter An object that builds search query
    * @param Request $request Laravel request object, this holds all data about request. Automatically populated.
    * @return \Illuminate\Http\JsonResponse
    */
    public function index(CrmAccountQueryFilter $filter, Request $request) {
        $data = CrmAccountService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
    * This method receives ID for the related model and returns the item to the client.
    *
    * @param $crmAccountId
    * @return mixed|null
    * @throws \Laravel\Octane\Exceptions\DdException
    */
    public function show($ref) {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = CrmAccountService::getByRef($ref);

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
    * This method created CrmAccount object on database.
    *
    * @param CrmAccountCreateRequest $request
    * @return mixed|null
    * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
    */
    public function store(CrmAccountCreateRequest $request) {
        $model = CrmAccountService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
    * This method updates CrmAccount object on database.
    *
    * @param $crmAccountId
    * @param CountryCreateRequest $request
    * @return mixed|null
    * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
    */
    public function update($crmAccountId, CrmAccountUpdateRequest $request) {
        $model = CrmAccountService::update($crmAccountId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
    * This method updates CrmAccount object on database.
    *
    * @param $crmAccountId
    * @param CountryCreateRequest $request
    * @return mixed|null
    * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
    */
    public function destroy($crmAccountId) {
        $model = CrmAccountService::delete($crmAccountId);

        return ResponsableFactory::makeResponse($this, $model);
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}