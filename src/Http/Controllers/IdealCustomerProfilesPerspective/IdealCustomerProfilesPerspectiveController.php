<?php

namespace NextDeveloper\CRM\Http\Controllers\IdealCustomerProfilesPerspective;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\IdealCustomerProfilesPerspective\IdealCustomerProfilesPerspectiveUpdateRequest;
use NextDeveloper\CRM\Database\Filters\IdealCustomerProfilesPerspectiveQueryFilter;
use NextDeveloper\CRM\Database\Models\IdealCustomerProfilesPerspective;
use NextDeveloper\CRM\Services\IdealCustomerProfilesPerspectiveService;
use NextDeveloper\CRM\Http\Requests\IdealCustomerProfilesPerspective\IdealCustomerProfilesPerspectiveCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags as TagsTrait;use NextDeveloper\Commons\Http\Traits\Addresses as AddressesTrait;
class IdealCustomerProfilesPerspectiveController extends AbstractController
{
    private $model = IdealCustomerProfilesPerspective::class;

    use TagsTrait;
    use AddressesTrait;
    /**
     * This method returns the list of idealcustomerprofilesperspectives.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  IdealCustomerProfilesPerspectiveQueryFilter $filter  An object that builds search query
     * @param  Request                                     $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IdealCustomerProfilesPerspectiveQueryFilter $filter, Request $request)
    {
        $data = IdealCustomerProfilesPerspectiveService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = IdealCustomerProfilesPerspectiveService::getActions();

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
        $actionId = IdealCustomerProfilesPerspectiveService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $idealCustomerProfilesPerspectiveId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = IdealCustomerProfilesPerspectiveService::getByRef($ref);

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
        $objects = IdealCustomerProfilesPerspectiveService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created IdealCustomerProfilesPerspective object on database.
     *
     * @param  IdealCustomerProfilesPerspectiveCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(IdealCustomerProfilesPerspectiveCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = IdealCustomerProfilesPerspectiveService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates IdealCustomerProfilesPerspective object on database.
     *
     * @param  $idealCustomerProfilesPerspectiveId
     * @param  IdealCustomerProfilesPerspectiveUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($idealCustomerProfilesPerspectiveId, IdealCustomerProfilesPerspectiveUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = IdealCustomerProfilesPerspectiveService::update($idealCustomerProfilesPerspectiveId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates IdealCustomerProfilesPerspective object on database.
     *
     * @param  $idealCustomerProfilesPerspectiveId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($idealCustomerProfilesPerspectiveId)
    {
        $model = IdealCustomerProfilesPerspectiveService::delete($idealCustomerProfilesPerspectiveId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
