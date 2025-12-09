<?php

namespace NextDeveloper\CRM\Http\Controllers\OpportunitiesPerformance;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\OpportunitiesPerformance\OpportunitiesPerformanceUpdateRequest;
use NextDeveloper\CRM\Database\Filters\OpportunitiesPerformanceQueryFilter;
use NextDeveloper\CRM\Database\Models\OpportunitiesPerformance;
use NextDeveloper\CRM\Services\OpportunitiesPerformanceService;
use NextDeveloper\CRM\Http\Requests\OpportunitiesPerformance\OpportunitiesPerformanceCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags as TagsTrait;use NextDeveloper\Commons\Http\Traits\Addresses as AddressesTrait;
class OpportunitiesPerformanceController extends AbstractController
{
    private $model = OpportunitiesPerformance::class;

    use TagsTrait;
    use AddressesTrait;
    /**
     * This method returns the list of opportunitiesperformances.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  OpportunitiesPerformanceQueryFilter $filter  An object that builds search query
     * @param  Request                             $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(OpportunitiesPerformanceQueryFilter $filter, Request $request)
    {
        $data = OpportunitiesPerformanceService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = OpportunitiesPerformanceService::getActions();

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
        $actionId = OpportunitiesPerformanceService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $opportunitiesPerformanceId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = OpportunitiesPerformanceService::getByRef($ref);

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
        $objects = OpportunitiesPerformanceService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created OpportunitiesPerformance object on database.
     *
     * @param  OpportunitiesPerformanceCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(OpportunitiesPerformanceCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = OpportunitiesPerformanceService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates OpportunitiesPerformance object on database.
     *
     * @param  $opportunitiesPerformanceId
     * @param  OpportunitiesPerformanceUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($opportunitiesPerformanceId, OpportunitiesPerformanceUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = OpportunitiesPerformanceService::update($opportunitiesPerformanceId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates OpportunitiesPerformance object on database.
     *
     * @param  $opportunitiesPerformanceId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($opportunitiesPerformanceId)
    {
        $model = OpportunitiesPerformanceService::delete($opportunitiesPerformanceId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
