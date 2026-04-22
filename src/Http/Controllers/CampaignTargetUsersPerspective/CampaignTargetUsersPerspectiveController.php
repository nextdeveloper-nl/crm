<?php

namespace NextDeveloper\CRM\Http\Controllers\CampaignTargetUsersPerspective;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\CampaignTargetUsersPerspective\CampaignTargetUsersPerspectiveUpdateRequest;
use NextDeveloper\CRM\Database\Filters\CampaignTargetUsersPerspectiveQueryFilter;
use NextDeveloper\CRM\Database\Models\CampaignTargetUsersPerspective;
use NextDeveloper\CRM\Services\CampaignTargetUsersPerspectiveService;
use NextDeveloper\CRM\Http\Requests\CampaignTargetUsersPerspective\CampaignTargetUsersPerspectiveCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags as TagsTrait;use NextDeveloper\Commons\Http\Traits\Addresses as AddressesTrait;
class CampaignTargetUsersPerspectiveController extends AbstractController
{
    private $model = CampaignTargetUsersPerspective::class;

    use TagsTrait;
    use AddressesTrait;
    /**
     * This method returns the list of campaigntargetusersperspectives.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  CampaignTargetUsersPerspectiveQueryFilter $filter  An object that builds search query
     * @param  Request                                   $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(CampaignTargetUsersPerspectiveQueryFilter $filter, Request $request)
    {
        $data = CampaignTargetUsersPerspectiveService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = CampaignTargetUsersPerspectiveService::getActions();

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
        $actionId = CampaignTargetUsersPerspectiveService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $campaignTargetUsersPerspectiveId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = CampaignTargetUsersPerspectiveService::getByRef($ref);

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
        $objects = CampaignTargetUsersPerspectiveService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created CampaignTargetUsersPerspective object on database.
     *
     * @param  CampaignTargetUsersPerspectiveCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(CampaignTargetUsersPerspectiveCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = CampaignTargetUsersPerspectiveService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates CampaignTargetUsersPerspective object on database.
     *
     * @param  $campaignTargetUsersPerspectiveId
     * @param  CampaignTargetUsersPerspectiveUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($campaignTargetUsersPerspectiveId, CampaignTargetUsersPerspectiveUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = CampaignTargetUsersPerspectiveService::update($campaignTargetUsersPerspectiveId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates CampaignTargetUsersPerspective object on database.
     *
     * @param  $campaignTargetUsersPerspectiveId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($campaignTargetUsersPerspectiveId)
    {
        $model = CampaignTargetUsersPerspectiveService::delete($campaignTargetUsersPerspectiveId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
