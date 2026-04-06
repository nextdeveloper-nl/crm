<?php

namespace NextDeveloper\CRM\Http\Controllers\LeoActiveCustomersPerspective;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\LeoActiveCustomersPerspective\LeoActiveCustomersPerspectiveUpdateRequest;
use NextDeveloper\CRM\Database\Filters\LeoActiveCustomersPerspectiveQueryFilter;
use NextDeveloper\CRM\Database\Models\LeoActiveCustomersPerspective;
use NextDeveloper\CRM\Services\LeoActiveCustomersPerspectiveService;
use NextDeveloper\CRM\Http\Requests\LeoActiveCustomersPerspective\LeoActiveCustomersPerspectiveCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags as TagsTrait;use NextDeveloper\Commons\Http\Traits\Addresses as AddressesTrait;
class LeoActiveCustomersPerspectiveController extends AbstractController
{
    private $model = LeoActiveCustomersPerspective::class;

    use TagsTrait;
    use AddressesTrait;
    /**
     * This method returns the list of leoactivecustomersperspectives.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  LeoActiveCustomersPerspectiveQueryFilter $filter  An object that builds search query
     * @param  Request                                  $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(LeoActiveCustomersPerspectiveQueryFilter $filter, Request $request)
    {
        $data = LeoActiveCustomersPerspectiveService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = LeoActiveCustomersPerspectiveService::getActions();

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
        $actionId = LeoActiveCustomersPerspectiveService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $leoActiveCustomersPerspectiveId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = LeoActiveCustomersPerspectiveService::getByRef($ref);

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
        $objects = LeoActiveCustomersPerspectiveService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created LeoActiveCustomersPerspective object on database.
     *
     * @param  LeoActiveCustomersPerspectiveCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(LeoActiveCustomersPerspectiveCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = LeoActiveCustomersPerspectiveService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates LeoActiveCustomersPerspective object on database.
     *
     * @param  $leoActiveCustomersPerspectiveId
     * @param  LeoActiveCustomersPerspectiveUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($leoActiveCustomersPerspectiveId, LeoActiveCustomersPerspectiveUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = LeoActiveCustomersPerspectiveService::update($leoActiveCustomersPerspectiveId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates LeoActiveCustomersPerspective object on database.
     *
     * @param  $leoActiveCustomersPerspectiveId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($leoActiveCustomersPerspectiveId)
    {
        $model = LeoActiveCustomersPerspectiveService::delete($leoActiveCustomersPerspectiveId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
