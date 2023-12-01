<?php

namespace NextDeveloper\CRM\Http\Controllers\Quotes;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Generator\Http\Traits\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\Quotes\QuotesUpdateRequest;
use NextDeveloper\CRM\Database\Filters\QuotesQueryFilter;
use NextDeveloper\CRM\Services\QuotesService;
use NextDeveloper\CRM\Http\Requests\Quotes\QuotesCreateRequest;

class QuotesController extends AbstractController
{
    /**
     * This method returns the list of quotes.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  QuotesQueryFilter $filter  An object that builds search query
     * @param  Request           $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(QuotesQueryFilter $filter, Request $request)
    {
        $data = QuotesService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $quotesId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = QuotesService::getByRef($ref);

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method created Quotes object on database.
     *
     * @param  QuotesCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(QuotesCreateRequest $request)
    {
        $model = QuotesService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates Quotes object on database.
     *
     * @param  $quotesId
     * @param  CountryCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($quotesId, QuotesUpdateRequest $request)
    {
        $model = QuotesService::update($quotesId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates Quotes object on database.
     *
     * @param  $quotesId
     * @param  CountryCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($quotesId)
    {
        $model = QuotesService::delete($quotesId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
