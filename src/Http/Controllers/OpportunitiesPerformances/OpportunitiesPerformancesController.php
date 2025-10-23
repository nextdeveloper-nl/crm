<?php

namespace NextDeveloper\CRM\Http\Controllers\OpportunitiesPerformances;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\OpportunitiesPerformances\OpportunitiesPerformancesUpdateRequest;
use NextDeveloper\CRM\Database\Filters\OpportunitiesPerformancesQueryFilter;
use NextDeveloper\CRM\Database\Models\OpportunitiesPerformances;
use NextDeveloper\CRM\Services\OpportunitiesPerformancesService;
use NextDeveloper\CRM\Http\Requests\OpportunitiesPerformances\OpportunitiesPerformancesCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags as TagsTrait;use NextDeveloper\Commons\Http\Traits\Addresses as AddressesTrait;
class OpportunitiesPerformancesController extends AbstractController
{
    private $model = OpportunitiesPerformances::class;

    use TagsTrait;
    use AddressesTrait;
    /**
     * This method returns the list of opportunitiesperformances.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  OpportunitiesPerformancesQueryFilter $filter  An object that builds search query
     * @param  Request                              $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(OpportunitiesPerformancesQueryFilter $filter, Request $request)
    {
        $data = OpportunitiesPerformancesService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = OpportunitiesPerformancesService::getActions();

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
        $actionId = OpportunitiesPerformancesService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $opportunitiesPerformancesId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = OpportunitiesPerformancesService::getByRef($ref);

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
        $objects = OpportunitiesPerformancesService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created OpportunitiesPerformances object on database.
     *
     * @param  OpportunitiesPerformancesCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(OpportunitiesPerformancesCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = OpportunitiesPerformancesService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates OpportunitiesPerformances object on database.
     *
     * @param  $opportunitiesPerformancesId
     * @param  OpportunitiesPerformancesUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($opportunitiesPerformancesId, OpportunitiesPerformancesUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = OpportunitiesPerformancesService::update($opportunitiesPerformancesId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates OpportunitiesPerformances object on database.
     *
     * @param  $opportunitiesPerformancesId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($opportunitiesPerformancesId)
    {
        $model = OpportunitiesPerformancesService::delete($opportunitiesPerformancesId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
