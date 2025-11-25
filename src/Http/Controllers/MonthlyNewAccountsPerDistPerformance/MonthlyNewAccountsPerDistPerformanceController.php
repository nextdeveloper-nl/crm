<?php

namespace NextDeveloper\CRM\Http\Controllers\MonthlyNewAccountsPerDistPerformance;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\MonthlyNewAccountsPerDistPerformance\MonthlyNewAccountsPerDistPerformanceUpdateRequest;
use NextDeveloper\CRM\Database\Filters\MonthlyNewAccountsPerDistPerformanceQueryFilter;
use NextDeveloper\CRM\Database\Models\MonthlyNewAccountsPerDistPerformance;
use NextDeveloper\CRM\Services\MonthlyNewAccountsPerDistPerformanceService;
use NextDeveloper\CRM\Http\Requests\MonthlyNewAccountsPerDistPerformance\MonthlyNewAccountsPerDistPerformanceCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags as TagsTrait;use NextDeveloper\Commons\Http\Traits\Addresses as AddressesTrait;
class MonthlyNewAccountsPerDistPerformanceController extends AbstractController
{
    private $model = MonthlyNewAccountsPerDistPerformance::class;

    use TagsTrait;
    use AddressesTrait;
    /**
     * This method returns the list of monthlynewaccountsperdistperformances.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  MonthlyNewAccountsPerDistPerformanceQueryFilter $filter  An object that builds search query
     * @param  Request                                         $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(MonthlyNewAccountsPerDistPerformanceQueryFilter $filter, Request $request)
    {
        $data = MonthlyNewAccountsPerDistPerformanceService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = MonthlyNewAccountsPerDistPerformanceService::getActions();

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
        $actionId = MonthlyNewAccountsPerDistPerformanceService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $monthlyNewAccountsPerDistPerformanceId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = MonthlyNewAccountsPerDistPerformanceService::getByRef($ref);

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
        $objects = MonthlyNewAccountsPerDistPerformanceService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created MonthlyNewAccountsPerDistPerformance object on database.
     *
     * @param  MonthlyNewAccountsPerDistPerformanceCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(MonthlyNewAccountsPerDistPerformanceCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = MonthlyNewAccountsPerDistPerformanceService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates MonthlyNewAccountsPerDistPerformance object on database.
     *
     * @param  $monthlyNewAccountsPerDistPerformanceId
     * @param  MonthlyNewAccountsPerDistPerformanceUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($monthlyNewAccountsPerDistPerformanceId, MonthlyNewAccountsPerDistPerformanceUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = MonthlyNewAccountsPerDistPerformanceService::update($monthlyNewAccountsPerDistPerformanceId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates MonthlyNewAccountsPerDistPerformance object on database.
     *
     * @param  $monthlyNewAccountsPerDistPerformanceId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($monthlyNewAccountsPerDistPerformanceId)
    {
        $model = MonthlyNewAccountsPerDistPerformanceService::delete($monthlyNewAccountsPerDistPerformanceId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
