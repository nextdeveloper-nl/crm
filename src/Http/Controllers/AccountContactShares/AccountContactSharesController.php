<?php

namespace NextDeveloper\CRM\Http\Controllers\AccountContactShares;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\AccountContactShares\AccountContactSharesUpdateRequest;
use NextDeveloper\CRM\Database\Filters\AccountContactSharesQueryFilter;
use NextDeveloper\CRM\Database\Models\AccountContactShares;
use NextDeveloper\CRM\Services\AccountContactSharesService;
use NextDeveloper\CRM\Http\Requests\AccountContactShares\AccountContactSharesCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags;use NextDeveloper\Commons\Http\Traits\Addresses;
class AccountContactSharesController extends AbstractController
{
    private $model = AccountContactShares::class;

    use Tags;
    use Addresses;
    /**
     * This method returns the list of account contact shares.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  AccountContactSharesQueryFilter $filter  An object that builds search query
     * @param  Request          $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(AccountContactSharesQueryFilter $filter, Request $request)
    {
        $data = AccountContactSharesService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = AccountContactSharesService::getActions();

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
        $actionId = AccountContactSharesService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $accountContactSharesId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = AccountContactSharesService::getByRef($ref);

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
        $objects = AccountContactSharesService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created AccountContactShares object on database.
     *
     * @param  AccountContactSharesCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(AccountContactSharesCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = AccountContactSharesService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates AccountContactShares object on database.
     *
     * @param  $accountContactSharesId
     * @param  AccountContactSharesUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($accountContactSharesId, AccountContactSharesUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = AccountContactSharesService::update($accountContactSharesId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates AccountContactShares object on database.
     *
     * @param  $accountContactSharesId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($accountContactSharesId)
    {
        $model = AccountContactSharesService::delete($accountContactSharesId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
