<?php

namespace NextDeveloper\CRM\Http\Controllers\MonthlyNewAccountsPerformance;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\MonthlyNewAccountsPerformance\MonthlyNewAccountsPerformanceUpdateRequest;
use NextDeveloper\CRM\Database\Filters\MonthlyNewAccountsPerformanceQueryFilter;
use NextDeveloper\CRM\Database\Models\MonthlyNewAccountsPerformance;
use NextDeveloper\CRM\Services\MonthlyNewAccountsPerformanceService;
use NextDeveloper\CRM\Http\Requests\MonthlyNewAccountsPerformance\MonthlyNewAccountsPerformanceCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags as TagsTrait;use NextDeveloper\Commons\Http\Traits\Addresses as AddressesTrait;
class MonthlyNewAccountsPerformanceController extends AbstractController
{
    private $model = MonthlyNewAccountsPerformance::class;

    use TagsTrait;
    use AddressesTrait;
    /**
     * This method returns the list of monthlynewaccountsperformances.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  MonthlyNewAccountsPerformanceQueryFilter $filter  An object that builds search query
     * @param  Request                                  $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(MonthlyNewAccountsPerformanceQueryFilter $filter, Request $request)
    {
        $data = MonthlyNewAccountsPerformanceService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = MonthlyNewAccountsPerformanceService::getActions();

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
        $actionId = MonthlyNewAccountsPerformanceService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $monthlyNewAccountsPerformanceId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = MonthlyNewAccountsPerformanceService::getByRef($ref);

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
        $objects = MonthlyNewAccountsPerformanceService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created MonthlyNewAccountsPerformance object on database.
     *
     * @param  MonthlyNewAccountsPerformanceCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(MonthlyNewAccountsPerformanceCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = MonthlyNewAccountsPerformanceService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates MonthlyNewAccountsPerformance object on database.
     *
     * @param  $monthlyNewAccountsPerformanceId
     * @param  MonthlyNewAccountsPerformanceUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($monthlyNewAccountsPerformanceId, MonthlyNewAccountsPerformanceUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = MonthlyNewAccountsPerformanceService::update($monthlyNewAccountsPerformanceId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates MonthlyNewAccountsPerformance object on database.
     *
     * @param  $monthlyNewAccountsPerformanceId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($monthlyNewAccountsPerformanceId)
    {
        $model = MonthlyNewAccountsPerformanceService::delete($monthlyNewAccountsPerformanceId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
