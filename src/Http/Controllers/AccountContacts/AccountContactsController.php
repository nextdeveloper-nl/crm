<?php

namespace NextDeveloper\CRM\Http\Controllers\AccountContacts;

use Illuminate\Http\Request;
use NextDeveloper\CRM\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\CRM\Http\Requests\AccountContacts\AccountContactsUpdateRequest;
use NextDeveloper\CRM\Database\Filters\AccountContactsQueryFilter;
use NextDeveloper\CRM\Database\Models\AccountContacts;
use NextDeveloper\CRM\Services\AccountContactsService;
use NextDeveloper\CRM\Http\Requests\AccountContacts\AccountContactsCreateRequest;
use NextDeveloper\CRM\Http\Requests\AccountContacts\AccountContactsCreateWithContactRequest;
use NextDeveloper\CRM\Http\Requests\AccountContacts\AccountContactsCopyAccountUserRequest;
use NextDeveloper\CRM\Http\Requests\AccountContacts\AccountContactsAgendaSearchRequest;
use NextDeveloper\Commons\Http\Traits\Tags;use NextDeveloper\Commons\Http\Traits\Addresses;
class AccountContactsController extends AbstractController
{
    private $model = AccountContacts::class;

    use Tags;
    use Addresses;
    /**
     * This method returns the list of account contacts.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  AccountContactsQueryFilter $filter  An object that builds search query
     * @param  Request          $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(AccountContactsQueryFilter $filter, Request $request)
    {
        $data = AccountContactsService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = AccountContactsService::getActions();

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
        $actionId = AccountContactsService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $accountContactsId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = AccountContactsService::getByRef($ref);

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
        $objects = AccountContactsService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created AccountContacts object on database.
     *
     * @param  AccountContactsCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(AccountContactsCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = AccountContactsService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates AccountContacts object on database.
     *
     * @param  $accountContactsId
     * @param  AccountContactsUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($accountContactsId, AccountContactsUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = AccountContactsService::update($accountContactsId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates AccountContacts object on database.
     *
     * @param  $accountContactsId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($accountContactsId)
    {
        $model = AccountContactsService::delete($accountContactsId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    /**
     * Creates a new Agenda contact and links it to a CRM account in one request. The work is done in the service.
     *
     * @param  AccountContactsCreateWithContactRequest $request
     * @return mixed|null
     */
    public function storeWithContact(AccountContactsCreateWithContactRequest $request)
    {
        if ($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $validated = $request->validated();

        $model = AccountContactsService::createWithContact($validated['contact'], collect($validated)->except('contact')->all());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * The caller's own Agenda contacts, for linking an existing contact to a CRM account. Needs no Agenda role.
     *
     * @param  AccountContactsAgendaSearchRequest $request
     * @return mixed|null
     */
    public function agendaContacts(AccountContactsAgendaSearchRequest $request)
    {
        $validated = $request->validated();

        return $this->withArray([
            'data' => AccountContactsService::ownAgendaContacts($validated['name'] ?? null, (int) ($validated['rowCount'] ?? 10)),
        ]);
    }

    /**
     * Everybody of a CRM account: the linked agenda contacts the caller may see plus the account's platform users.
     *
     * @param  $crmAccountsId
     * @return mixed|null
     */
    public function people($crmAccountsId)
    {
        return $this->withArray(['data' => AccountContactsService::people($crmAccountsId)]);
    }

    /**
     * Makes an own, private agenda contact from an account user and links it to the account.
     *
     * @param  AccountContactsCopyAccountUserRequest $request
     * @return mixed|null
     */
    public function copyAccountUser(AccountContactsCopyAccountUserRequest $request)
    {
        $model = AccountContactsService::copyAccountUser($request->validated()['crm_user_id']);

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * Gives the caller an own, editable copy of a contact that was shared with them through the account.
     *
     * @param  $accountContactsId
     * @return mixed|null
     */
    public function copy($accountContactsId)
    {
        $model = AccountContactsService::copy($accountContactsId);

        return ResponsableFactory::makeResponse($this, $model);
    }


}
