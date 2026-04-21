<?php

namespace NextDeveloper\CRM\Http\Controllers\CampaignTargetsPerspective;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\CampaignTargetsPerspective\CampaignTargetsPerspectiveUpdateRequest;
use NextDeveloper\CRM\Database\Filters\CampaignTargetsPerspectiveQueryFilter;
use NextDeveloper\CRM\Database\Models\CampaignTargetsPerspective;
use NextDeveloper\CRM\Services\CampaignTargetsPerspectiveService;
use NextDeveloper\CRM\Http\Requests\CampaignTargetsPerspective\CampaignTargetsPerspectiveCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags as TagsTrait;use NextDeveloper\Commons\Http\Traits\Addresses as AddressesTrait;
class CampaignTargetsPerspectiveController extends AbstractController
{
    private $model = CampaignTargetsPerspective::class;

    use TagsTrait;
    use AddressesTrait;
    /**
     * This method returns the list of campaigntargetsperspectives.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  CampaignTargetsPerspectiveQueryFilter $filter  An object that builds search query
     * @param  Request                               $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(CampaignTargetsPerspectiveQueryFilter $filter, Request $request)
    {
        $data = CampaignTargetsPerspectiveService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = CampaignTargetsPerspectiveService::getActions();

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
        $actionId = CampaignTargetsPerspectiveService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $campaignTargetsPerspectiveId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = CampaignTargetsPerspectiveService::getByRef($ref);

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
        $objects = CampaignTargetsPerspectiveService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created CampaignTargetsPerspective object on database.
     *
     * @param  CampaignTargetsPerspectiveCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(CampaignTargetsPerspectiveCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = CampaignTargetsPerspectiveService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates CampaignTargetsPerspective object on database.
     *
     * @param  $campaignTargetsPerspectiveId
     * @param  CampaignTargetsPerspectiveUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($campaignTargetsPerspectiveId, CampaignTargetsPerspectiveUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = CampaignTargetsPerspectiveService::update($campaignTargetsPerspectiveId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates CampaignTargetsPerspective object on database.
     *
     * @param  $campaignTargetsPerspectiveId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($campaignTargetsPerspectiveId)
    {
        $model = CampaignTargetsPerspectiveService::delete($campaignTargetsPerspectiveId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
