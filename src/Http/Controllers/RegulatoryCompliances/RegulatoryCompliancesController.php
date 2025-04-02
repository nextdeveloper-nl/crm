<?php

namespace NextDeveloper\CRM\Http\Controllers\RegulatoryCompliances;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\RegulatoryCompliances\RegulatoryCompliancesUpdateRequest;
use NextDeveloper\CRM\Database\Filters\RegulatoryCompliancesQueryFilter;
use NextDeveloper\CRM\Database\Models\RegulatoryCompliances;
use NextDeveloper\CRM\Services\RegulatoryCompliancesService;
use NextDeveloper\CRM\Http\Requests\RegulatoryCompliances\RegulatoryCompliancesCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags as TagsTrait;use NextDeveloper\Commons\Http\Traits\Addresses as AddressesTrait;
class RegulatoryCompliancesController extends AbstractController
{
    private $model = RegulatoryCompliances::class;

    use TagsTrait;
    use AddressesTrait;
    /**
     * This method returns the list of regulatorycompliances.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  RegulatoryCompliancesQueryFilter $filter  An object that builds search query
     * @param  Request                          $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(RegulatoryCompliancesQueryFilter $filter, Request $request)
    {
        $data = RegulatoryCompliancesService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = RegulatoryCompliancesService::getActions();

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
        $actionId = RegulatoryCompliancesService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $regulatoryCompliancesId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = RegulatoryCompliancesService::getByRef($ref);

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
        $objects = RegulatoryCompliancesService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created RegulatoryCompliances object on database.
     *
     * @param  RegulatoryCompliancesCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(RegulatoryCompliancesCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = RegulatoryCompliancesService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates RegulatoryCompliances object on database.
     *
     * @param  $regulatoryCompliancesId
     * @param  RegulatoryCompliancesUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($regulatoryCompliancesId, RegulatoryCompliancesUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = RegulatoryCompliancesService::update($regulatoryCompliancesId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates RegulatoryCompliances object on database.
     *
     * @param  $regulatoryCompliancesId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($regulatoryCompliancesId)
    {
        $model = RegulatoryCompliancesService::delete($regulatoryCompliancesId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
