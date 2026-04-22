<?php

namespace NextDeveloper\CRM\Http\Controllers\UserEmailsPerspective;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\UserEmailsPerspective\UserEmailsPerspectiveUpdateRequest;
use NextDeveloper\CRM\Database\Filters\UserEmailsPerspectiveQueryFilter;
use NextDeveloper\CRM\Database\Models\UserEmailsPerspective;
use NextDeveloper\CRM\Services\UserEmailsPerspectiveService;
use NextDeveloper\CRM\Http\Requests\UserEmailsPerspective\UserEmailsPerspectiveCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags as TagsTrait;use NextDeveloper\Commons\Http\Traits\Addresses as AddressesTrait;
class UserEmailsPerspectiveController extends AbstractController
{
    private $model = UserEmailsPerspective::class;

    use TagsTrait;
    use AddressesTrait;
    /**
     * This method returns the list of useremailsperspectives.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  UserEmailsPerspectiveQueryFilter $filter  An object that builds search query
     * @param  Request                          $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(UserEmailsPerspectiveQueryFilter $filter, Request $request)
    {
        $data = UserEmailsPerspectiveService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = UserEmailsPerspectiveService::getActions();

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
        $actionId = UserEmailsPerspectiveService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $userEmailsPerspectiveId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = UserEmailsPerspectiveService::getByRef($ref);

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
        $objects = UserEmailsPerspectiveService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created UserEmailsPerspective object on database.
     *
     * @param  UserEmailsPerspectiveCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(UserEmailsPerspectiveCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = UserEmailsPerspectiveService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates UserEmailsPerspective object on database.
     *
     * @param  $userEmailsPerspectiveId
     * @param  UserEmailsPerspectiveUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($userEmailsPerspectiveId, UserEmailsPerspectiveUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = UserEmailsPerspectiveService::update($userEmailsPerspectiveId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates UserEmailsPerspective object on database.
     *
     * @param  $userEmailsPerspectiveId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($userEmailsPerspectiveId)
    {
        $model = UserEmailsPerspectiveService::delete($userEmailsPerspectiveId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
