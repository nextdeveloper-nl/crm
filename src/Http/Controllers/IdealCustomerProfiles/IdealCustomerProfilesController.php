<?php

namespace NextDeveloper\CRM\Http\Controllers\IdealCustomerProfiles;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\IdealCustomerProfiles\IdealCustomerProfilesUpdateRequest;
use NextDeveloper\CRM\Database\Filters\IdealCustomerProfilesQueryFilter;
use NextDeveloper\CRM\Database\Models\IdealCustomerProfiles;
use NextDeveloper\CRM\Services\IdealCustomerProfilesService;
use NextDeveloper\CRM\Http\Requests\IdealCustomerProfiles\IdealCustomerProfilesCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags;use NextDeveloper\Commons\Http\Traits\Addresses;
class IdealCustomerProfilesController extends AbstractController
{
    private $model = IdealCustomerProfiles::class;

    use Tags;
    use Addresses;
    /**
     * This method returns the list of idealcustomerprofiles.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  IdealCustomerProfilesQueryFilter $filter  An object that builds search query
     * @param  Request                          $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IdealCustomerProfilesQueryFilter $filter, Request $request)
    {
        $data = IdealCustomerProfilesService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = IdealCustomerProfilesService::getActions();

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
        $actionId = IdealCustomerProfilesService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $idealCustomerProfilesId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = IdealCustomerProfilesService::getByRef($ref);

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
        $objects = IdealCustomerProfilesService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created IdealCustomerProfiles object on database.
     *
     * @param  IdealCustomerProfilesCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(IdealCustomerProfilesCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = IdealCustomerProfilesService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates IdealCustomerProfiles object on database.
     *
     * @param  $idealCustomerProfilesId
     * @param  IdealCustomerProfilesUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($idealCustomerProfilesId, IdealCustomerProfilesUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = IdealCustomerProfilesService::update($idealCustomerProfilesId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates IdealCustomerProfiles object on database.
     *
     * @param  $idealCustomerProfilesId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($idealCustomerProfilesId)
    {
        $model = IdealCustomerProfilesService::delete($idealCustomerProfilesId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
