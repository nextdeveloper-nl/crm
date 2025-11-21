<?php

namespace NextDeveloper\CRM\Http\Controllers\AccountManagersPerformance;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\AccountManagersPerformance\AccountManagersPerformanceUpdateRequest;
use NextDeveloper\CRM\Database\Filters\AccountManagersPerformanceQueryFilter;
use NextDeveloper\CRM\Database\Models\AccountManagersPerformance;
use NextDeveloper\CRM\Services\AccountManagersPerformanceService;
use NextDeveloper\CRM\Http\Requests\AccountManagersPerformance\AccountManagersPerformanceCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags as TagsTrait;use NextDeveloper\Commons\Http\Traits\Addresses as AddressesTrait;
class AccountManagersPerformanceController extends AbstractController
{
    private $model = AccountManagersPerformance::class;

    use TagsTrait;
    use AddressesTrait;
    /**
     * This method returns the list of accountmanagersperformances.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  AccountManagersPerformanceQueryFilter $filter  An object that builds search query
     * @param  Request                               $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(AccountManagersPerformanceQueryFilter $filter, Request $request)
    {
        $data = AccountManagersPerformanceService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = AccountManagersPerformanceService::getActions();

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
        $actionId = AccountManagersPerformanceService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $accountManagersPerformanceId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = AccountManagersPerformanceService::getByRef($ref);

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
        $objects = AccountManagersPerformanceService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created AccountManagersPerformance object on database.
     *
     * @param  AccountManagersPerformanceCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(AccountManagersPerformanceCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = AccountManagersPerformanceService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates AccountManagersPerformance object on database.
     *
     * @param  $accountManagersPerformanceId
     * @param  AccountManagersPerformanceUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($accountManagersPerformanceId, AccountManagersPerformanceUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = AccountManagersPerformanceService::update($accountManagersPerformanceId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates AccountManagersPerformance object on database.
     *
     * @param  $accountManagersPerformanceId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($accountManagersPerformanceId)
    {
        $model = AccountManagersPerformanceService::delete($accountManagersPerformanceId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
