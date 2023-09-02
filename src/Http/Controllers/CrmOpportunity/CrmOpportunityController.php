<?php

namespace NextDeveloper\CRM\Http\Controllers\CrmOpportunity;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Generator\Http\Traits\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\CrmOpportunity\CrmOpportunityUpdateRequest;
use NextDeveloper\CRM\Database\Filters\CrmOpportunityQueryFilter;
use NextDeveloper\CRM\Services\CrmOpportunityService;
use NextDeveloper\CRM\Http\Requests\CrmOpportunity\CrmOpportunityCreateRequest;

class CrmOpportunityController extends AbstractController
{
    /**
    * This method returns the list of crmopportunities.
    *
    * optional http params:
    * - paginate: If you set paginate parameter, the result will be returned paginated.
    *
    * @param CrmOpportunityQueryFilter $filter An object that builds search query
    * @param Request $request Laravel request object, this holds all data about request. Automatically populated.
    * @return \Illuminate\Http\JsonResponse
    */
    public function index(CrmOpportunityQueryFilter $filter, Request $request) {
        $data = CrmOpportunityService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
    * This method receives ID for the related model and returns the item to the client.
    *
    * @param $crmOpportunityId
    * @return mixed|null
    * @throws \Laravel\Octane\Exceptions\DdException
    */
    public function show($ref) {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = CrmOpportunityService::getByRef($ref);

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
    * This method created CrmOpportunity object on database.
    *
    * @param CrmOpportunityCreateRequest $request
    * @return mixed|null
    * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
    */
    public function store(CrmOpportunityCreateRequest $request) {
        $model = CrmOpportunityService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
    * This method updates CrmOpportunity object on database.
    *
    * @param $crmOpportunityId
    * @param CountryCreateRequest $request
    * @return mixed|null
    * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
    */
    public function update($crmOpportunityId, CrmOpportunityUpdateRequest $request) {
        $model = CrmOpportunityService::update($crmOpportunityId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
    * This method updates CrmOpportunity object on database.
    *
    * @param $crmOpportunityId
    * @param CountryCreateRequest $request
    * @return mixed|null
    * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
    */
    public function destroy($crmOpportunityId) {
        $model = CrmOpportunityService::delete($crmOpportunityId);

        return ResponsableFactory::makeResponse($this, $model);
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}