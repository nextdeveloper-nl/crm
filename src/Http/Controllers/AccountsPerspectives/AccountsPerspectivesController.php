<?php

namespace NextDeveloper\CRM\Http\Controllers\AccountsPerspectives;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Traits\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\AccountsPerspectives\AccountsPerspectivesUpdateRequest;
use NextDeveloper\CRM\Database\Filters\AccountsPerspectivesQueryFilter;
use NextDeveloper\CRM\Database\Models\AccountsPerspectives;
use NextDeveloper\CRM\Services\AccountsPerspectivesService;
use NextDeveloper\CRM\Http\Requests\AccountsPerspectives\AccountsPerspectivesCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags;use NextDeveloper\Commons\Http\Traits\Addresses;
class AccountsPerspectivesController extends AbstractController
{
    private $model = AccountsPerspectives::class;

    use Tags;
    use Addresses;
    /**
     * This method returns the list of accountsperspectives.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  AccountsPerspectivesQueryFilter $filter  An object that builds search query
     * @param  Request                         $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(AccountsPerspectivesQueryFilter $filter, Request $request)
    {
        $data = AccountsPerspectivesService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $accountsPerspectivesId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = AccountsPerspectivesService::getByRef($ref);

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
        $objects = AccountsPerspectivesService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created AccountsPerspectives object on database.
     *
     * @param  AccountsPerspectivesCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(AccountsPerspectivesCreateRequest $request)
    {
        $model = AccountsPerspectivesService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates AccountsPerspectives object on database.
     *
     * @param  $accountsPerspectivesId
     * @param  CountryCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($accountsPerspectivesId, AccountsPerspectivesUpdateRequest $request)
    {
        $model = AccountsPerspectivesService::update($accountsPerspectivesId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates AccountsPerspectives object on database.
     *
     * @param  $accountsPerspectivesId
     * @param  CountryCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($accountsPerspectivesId)
    {
        $model = AccountsPerspectivesService::delete($accountsPerspectivesId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
