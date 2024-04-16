<?php

namespace NextDeveloper\CRM\Http\Controllers\UserManagers;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\UserManagers\UserManagersUpdateRequest;
use NextDeveloper\CRM\Database\Filters\UserManagersQueryFilter;
use NextDeveloper\CRM\Database\Models\UserManagers;
use NextDeveloper\CRM\Services\UserManagersService;
use NextDeveloper\CRM\Http\Requests\UserManagers\UserManagersCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags;
class UserManagersController extends AbstractController
{
    private $model = UserManagers::class;

    use Tags;
    /**
     * This method returns the list of usermanagers.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  UserManagersQueryFilter $filter  An object that builds search query
     * @param  Request                 $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(UserManagersQueryFilter $filter, Request $request)
    {
        $data = UserManagersService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $userManagersId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = UserManagersService::getByRef($ref);

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
        $objects = UserManagersService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created UserManagers object on database.
     *
     * @param  UserManagersCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(UserManagersCreateRequest $request)
    {
        $model = UserManagersService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates UserManagers object on database.
     *
     * @param  $userManagersId
     * @param  CountryCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($userManagersId, UserManagersUpdateRequest $request)
    {
        $model = UserManagersService::update($userManagersId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates UserManagers object on database.
     *
     * @param  $userManagersId
     * @param  CountryCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($userManagersId)
    {
        $model = UserManagersService::delete($userManagersId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
