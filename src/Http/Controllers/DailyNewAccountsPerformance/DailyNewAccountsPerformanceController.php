<?php

namespace NextDeveloper\CRM\Http\Controllers\DailyNewAccountsPerformance;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\DailyNewAccountsPerformance\DailyNewAccountsPerformanceUpdateRequest;
use NextDeveloper\CRM\Database\Filters\DailyNewAccountsPerformanceQueryFilter;
use NextDeveloper\CRM\Database\Models\DailyNewAccountsPerformance;
use NextDeveloper\CRM\Services\DailyNewAccountsPerformanceService;
use NextDeveloper\CRM\Http\Requests\DailyNewAccountsPerformance\DailyNewAccountsPerformanceCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags as TagsTrait;use NextDeveloper\Commons\Http\Traits\Addresses as AddressesTrait;
class DailyNewAccountsPerformanceController extends AbstractController
{
    private $model = DailyNewAccountsPerformance::class;

    use TagsTrait;
    use AddressesTrait;
    /**
     * This method returns the list of dailynewaccountsperformances.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  DailyNewAccountsPerformanceQueryFilter $filter  An object that builds search query
     * @param  Request                                $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(DailyNewAccountsPerformanceQueryFilter $filter, Request $request)
    {
        $data = DailyNewAccountsPerformanceService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = DailyNewAccountsPerformanceService::getActions();

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
        $actionId = DailyNewAccountsPerformanceService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $dailyNewAccountsPerformanceId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = DailyNewAccountsPerformanceService::getByRef($ref);

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
        $objects = DailyNewAccountsPerformanceService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created DailyNewAccountsPerformance object on database.
     *
     * @param  DailyNewAccountsPerformanceCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(DailyNewAccountsPerformanceCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = DailyNewAccountsPerformanceService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates DailyNewAccountsPerformance object on database.
     *
     * @param  $dailyNewAccountsPerformanceId
     * @param  DailyNewAccountsPerformanceUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($dailyNewAccountsPerformanceId, DailyNewAccountsPerformanceUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = DailyNewAccountsPerformanceService::update($dailyNewAccountsPerformanceId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates DailyNewAccountsPerformance object on database.
     *
     * @param  $dailyNewAccountsPerformanceId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($dailyNewAccountsPerformanceId)
    {
        $model = DailyNewAccountsPerformanceService::delete($dailyNewAccountsPerformanceId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
