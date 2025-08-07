<?php

namespace NextDeveloper\CRM\Http\Controllers\AccountManagersPerformancesPerspective;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\AccountManagersPerformancesPerspective\AccountManagersPerformancesPerspectiveUpdateRequest;
use NextDeveloper\CRM\Database\Filters\AccountManagersPerformancesPerspectiveQueryFilter;
use NextDeveloper\CRM\Database\Models\AccountManagersPerformancesPerspective;
use NextDeveloper\CRM\Services\AccountManagersPerformancesPerspectiveService;
use NextDeveloper\CRM\Http\Requests\AccountManagersPerformancesPerspective\AccountManagersPerformancesPerspectiveCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags as TagsTrait;use NextDeveloper\Commons\Http\Traits\Addresses as AddressesTrait;
class AccountManagersPerformancesPerspectiveController extends AbstractController
{
    private $model = AccountManagersPerformancesPerspective::class;

    use TagsTrait;
    use AddressesTrait;
    /**
     * This method returns the list of accountmanagersperformancesperspectives.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  AccountManagersPerformancesPerspectiveQueryFilter $filter  An object that builds search query
     * @param  Request                                           $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(AccountManagersPerformancesPerspectiveQueryFilter $filter, Request $request)
    {
        $data = AccountManagersPerformancesPerspectiveService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = AccountManagersPerformancesPerspectiveService::getActions();

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
        $actionId = AccountManagersPerformancesPerspectiveService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $accountManagersPerformancesPerspectiveId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = AccountManagersPerformancesPerspectiveService::getByRef($ref);

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
        $objects = AccountManagersPerformancesPerspectiveService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created AccountManagersPerformancesPerspective object on database.
     *
     * @param  AccountManagersPerformancesPerspectiveCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(AccountManagersPerformancesPerspectiveCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = AccountManagersPerformancesPerspectiveService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates AccountManagersPerformancesPerspective object on database.
     *
     * @param  $accountManagersPerformancesPerspectiveId
     * @param  AccountManagersPerformancesPerspectiveUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($accountManagersPerformancesPerspectiveId, AccountManagersPerformancesPerspectiveUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = AccountManagersPerformancesPerspectiveService::update($accountManagersPerformancesPerspectiveId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates AccountManagersPerformancesPerspective object on database.
     *
     * @param  $accountManagersPerformancesPerspectiveId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($accountManagersPerformancesPerspectiveId)
    {
        $model = AccountManagersPerformancesPerspectiveService::delete($accountManagersPerformancesPerspectiveId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
