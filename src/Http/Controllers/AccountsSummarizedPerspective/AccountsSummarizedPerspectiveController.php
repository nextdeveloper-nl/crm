<?php

namespace NextDeveloper\CRM\Http\Controllers\AccountsSummarizedPerspective;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\AccountsSummarizedPerspective\AccountsSummarizedPerspectiveUpdateRequest;
use NextDeveloper\CRM\Database\Filters\AccountsSummarizedPerspectiveQueryFilter;
use NextDeveloper\CRM\Database\Models\AccountsSummarizedPerspective;
use NextDeveloper\CRM\Services\AccountsSummarizedPerspectiveService;
use NextDeveloper\CRM\Http\Requests\AccountsSummarizedPerspective\AccountsSummarizedPerspectiveCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags as TagsTrait;use NextDeveloper\Commons\Http\Traits\Addresses as AddressesTrait;
class AccountsSummarizedPerspectiveController extends AbstractController
{
    private $model = AccountsSummarizedPerspective::class;

    use TagsTrait;
    use AddressesTrait;
    /**
     * This method returns the list of accountssummarizedperspectives.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  AccountsSummarizedPerspectiveQueryFilter $filter  An object that builds search query
     * @param  Request                                  $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(AccountsSummarizedPerspectiveQueryFilter $filter, Request $request)
    {
        $data = AccountsSummarizedPerspectiveService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = AccountsSummarizedPerspectiveService::getActions();

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
        $actionId = AccountsSummarizedPerspectiveService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $accountsSummarizedPerspectiveId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = AccountsSummarizedPerspectiveService::getByRef($ref);

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
        $objects = AccountsSummarizedPerspectiveService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created AccountsSummarizedPerspective object on database.
     *
     * @param  AccountsSummarizedPerspectiveCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(AccountsSummarizedPerspectiveCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = AccountsSummarizedPerspectiveService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates AccountsSummarizedPerspective object on database.
     *
     * @param  $accountsSummarizedPerspectiveId
     * @param  AccountsSummarizedPerspectiveUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($accountsSummarizedPerspectiveId, AccountsSummarizedPerspectiveUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = AccountsSummarizedPerspectiveService::update($accountsSummarizedPerspectiveId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates AccountsSummarizedPerspective object on database.
     *
     * @param  $accountsSummarizedPerspectiveId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($accountsSummarizedPerspectiveId)
    {
        $model = AccountsSummarizedPerspectiveService::delete($accountsSummarizedPerspectiveId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
