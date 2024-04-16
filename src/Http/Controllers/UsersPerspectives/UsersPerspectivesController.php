<?php

namespace NextDeveloper\CRM\Http\Controllers\UsersPerspectives;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\UsersPerspectives\UsersPerspectivesUpdateRequest;
use NextDeveloper\CRM\Database\Filters\UsersPerspectivesQueryFilter;
use NextDeveloper\CRM\Database\Models\UsersPerspectives;
use NextDeveloper\CRM\Services\UsersPerspectivesService;
use NextDeveloper\CRM\Http\Requests\UsersPerspectives\UsersPerspectivesCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags;use NextDeveloper\Commons\Http\Traits\Addresses;
class UsersPerspectivesController extends AbstractController
{
    private $model = UsersPerspectives::class;

    use Tags;
    use Addresses;
    /**
     * This method returns the list of usersperspectives.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  UsersPerspectivesQueryFilter $filter  An object that builds search query
     * @param  Request                      $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(UsersPerspectivesQueryFilter $filter, Request $request)
    {
        $data = UsersPerspectivesService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $usersPerspectivesId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = UsersPerspectivesService::getByRef($ref);

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
        $objects = UsersPerspectivesService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created UsersPerspectives object on database.
     *
     * @param  UsersPerspectivesCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(UsersPerspectivesCreateRequest $request)
    {
        $model = UsersPerspectivesService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates UsersPerspectives object on database.
     *
     * @param  $usersPerspectivesId
     * @param  CountryCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($usersPerspectivesId, UsersPerspectivesUpdateRequest $request)
    {
        $model = UsersPerspectivesService::update($usersPerspectivesId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates UsersPerspectives object on database.
     *
     * @param  $usersPerspectivesId
     * @param  CountryCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($usersPerspectivesId)
    {
        $model = UsersPerspectivesService::delete($usersPerspectivesId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
