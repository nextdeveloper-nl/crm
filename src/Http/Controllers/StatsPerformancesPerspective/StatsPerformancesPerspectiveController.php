<?php

namespace NextDeveloper\CRM\Http\Controllers\StatsPerformancesPerspective;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\StatsPerformancesPerspective\StatsPerformancesPerspectiveUpdateRequest;
use NextDeveloper\CRM\Database\Filters\StatsPerformancesPerspectiveQueryFilter;
use NextDeveloper\CRM\Database\Models\StatsPerformancesPerspective;
use NextDeveloper\CRM\Services\StatsPerformancesPerspectiveService;
use NextDeveloper\CRM\Http\Requests\StatsPerformancesPerspective\StatsPerformancesPerspectiveCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags as TagsTrait;use NextDeveloper\Commons\Http\Traits\Addresses as AddressesTrait;
class StatsPerformancesPerspectiveController extends AbstractController
{
    private $model = StatsPerformancesPerspective::class;

    use TagsTrait;
    use AddressesTrait;
    /**
     * This method returns the list of statsperformancesperspectives.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  StatsPerformancesPerspectiveQueryFilter $filter  An object that builds search query
     * @param  Request                                 $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(StatsPerformancesPerspectiveQueryFilter $filter, Request $request)
    {
        $data = StatsPerformancesPerspectiveService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = StatsPerformancesPerspectiveService::getActions();

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
        $actionId = StatsPerformancesPerspectiveService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $statsPerformancesPerspectiveId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = StatsPerformancesPerspectiveService::getByRef($ref);

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
        $objects = StatsPerformancesPerspectiveService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created StatsPerformancesPerspective object on database.
     *
     * @param  StatsPerformancesPerspectiveCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(StatsPerformancesPerspectiveCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = StatsPerformancesPerspectiveService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates StatsPerformancesPerspective object on database.
     *
     * @param  $statsPerformancesPerspectiveId
     * @param  StatsPerformancesPerspectiveUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($statsPerformancesPerspectiveId, StatsPerformancesPerspectiveUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = StatsPerformancesPerspectiveService::update($statsPerformancesPerspectiveId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates StatsPerformancesPerspective object on database.
     *
     * @param  $statsPerformancesPerspectiveId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($statsPerformancesPerspectiveId)
    {
        $model = StatsPerformancesPerspectiveService::delete($statsPerformancesPerspectiveId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
