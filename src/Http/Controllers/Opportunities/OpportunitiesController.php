<?php

namespace NextDeveloper\CRM\Http\Controllers\Opportunities;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\Opportunities\OpportunitiesUpdateRequest;
use NextDeveloper\CRM\Database\Filters\OpportunitiesQueryFilter;
use NextDeveloper\CRM\Database\Models\Opportunities;
use NextDeveloper\CRM\Services\OpportunitiesService;
use NextDeveloper\CRM\Http\Requests\Opportunities\OpportunitiesCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags;
class OpportunitiesController extends AbstractController
{
    private $model = Opportunities::class;

    use Tags;
    /**
     * This method returns the list of opportunities.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  OpportunitiesQueryFilter $filter  An object that builds search query
     * @param  Request                  $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(OpportunitiesQueryFilter $filter, Request $request)
    {
        $data = OpportunitiesService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $opportunitiesId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = OpportunitiesService::getByRef($ref);

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
        $objects = OpportunitiesService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created Opportunities object on database.
     *
     * @param  OpportunitiesCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(OpportunitiesCreateRequest $request)
    {
        $model = OpportunitiesService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates Opportunities object on database.
     *
     * @param  $opportunitiesId
     * @param  CountryCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($opportunitiesId, OpportunitiesUpdateRequest $request)
    {
        $model = OpportunitiesService::update($opportunitiesId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates Opportunities object on database.
     *
     * @param  $opportunitiesId
     * @param  CountryCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($opportunitiesId)
    {
        $model = OpportunitiesService::delete($opportunitiesId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
