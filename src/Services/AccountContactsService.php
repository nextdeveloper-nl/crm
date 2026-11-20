<?php

namespace NextDeveloper\CRM\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use NextDeveloper\Agenda\Database\Models\AddressBooks;
use NextDeveloper\Agenda\Database\Models\Contacts;
use NextDeveloper\Agenda\Services\ContactsService;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\CRM\Database\Filters\AccountContactsQueryFilter;
use NextDeveloper\CRM\Database\Models\AccountContactShares;
use NextDeveloper\CRM\Database\Models\AccountContacts;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\CRM\Helpers\TenantHelper;
use NextDeveloper\CRM\Services\AbstractServices\AbstractAccountContactsService;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * This class is responsible from managing the data for AccountContacts
 *
 * Class AccountContactsService.
 *
 * An account contact is a link between an Agenda contact (private to its owner) and a CRM account. The link carries
 * the role of the contact at that account, a primary flag, free form data and the visibility of the link:
 *  - private:  only the owner of the contact sees it
 *  - team:     everybody of the owner's account who can see the CRM account sees it
 *  - specific: only the owner and the users in crm_account_contact_shares see it
 * Everybody but the owner gets read access only; editing the contact stays with the owner and a team member who wants
 * to change something uses copy() to get an own copy.
 *
 * @package NextDeveloper\CRM\Database\Models
 */
class AccountContactsService extends AbstractAccountContactsService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    /**
     * Query of the links the caller may see: own links, team links of the caller's account on CRM accounts the caller
     * can see, and links explicitly shared with the caller. The role scope is removed because roles only know
     * "own rows" or "everything of the account"; the visibility rules above are applied here instead.
     */
    public static function visibleQuery(): Builder
    {
        $meId = (int) UserHelper::me()->id;
        $accountId = (int) UserHelper::currentAccount()->id;

        return AccountContacts::withoutGlobalScope(AuthorizationScope::class)
            ->where(function (Builder $query) use ($meId, $accountId) {
                // Own links
                $query->where('iam_user_id', $meId)
                    // Team links: same account as the owner and the CRM account is visible to the caller
                    // (Accounts keeps its role + tenant scopes, so a manager only gets managed accounts).
                    ->orWhere(function (Builder $team) use ($accountId) {
                        $team->where('visibility', AccountContacts::VISIBILITY_TEAM)
                            ->where('iam_account_id', $accountId)
                            ->whereIn('crm_account_id', Accounts::select('id'));
                    })
                    // Links shared with the caller
                    ->orWhere(function (Builder $specific) use ($meId, $accountId) {
                        $specific->where('visibility', AccountContacts::VISIBILITY_SPECIFIC)
                            ->where('iam_account_id', $accountId)
                            ->whereIn(
                                'id',
                                AccountContactShares::withoutGlobalScopes()
                                    ->where('shared_iam_user_id', $meId)
                                    ->select('crm_account_contact_id')
                            );
                    });
            });
    }

    /**
     * Lists the visible links (see visibleQuery()). Same params as the generated service.
     */
    public static function get(?AccountContactsQueryFilter $filter = null, array $params = []): Collection|LengthAwarePaginator
    {
        $filter = $filter ?? new AccountContactsQueryFilter(new \Illuminate\Http\Request());

        if (array_key_exists('orderBy', $params)) {
            $filter->orderBy($params['orderBy']);
        }

        $model = self::visibleQuery()->filter($filter);

        if (!array_key_exists('paginate', $params)) {
            return $model->get();
        }

        $perPage = intval($params['per_page'] ?? config('commons.pagination.per_page', 20)) ?: 20;
        $page = intval($params['page'] ?? 1);

        $count = $model->count();
        $items = $model->skip(($page - 1) * $perPage)->take($perPage)->get();

        return new \Illuminate\Pagination\LengthAwarePaginator($items, $count, $perPage, $page);
    }

    /** A single link, only when the caller may see it. */
    public static function getByRef($ref): ?AccountContacts
    {
        return self::visibleQuery()->where('uuid', $ref)->first();
    }

    /**
     * Links one of the caller's own Agenda contacts to a CRM account the caller can see. The owner, the owning account
     * and the visibility default always come from the acting user, never from the payload.
     */
    public static function create(array $data)
    {
        unset($data['iam_user_id'], $data['iam_account_id']);

        TenantHelper::assertVisible(Accounts::class, $data['crm_account_id'] ?? null);

        // Only the owner may link a contact (Agenda scope would hide foreign contacts anyway; the explicit
        // iam_user_id check keeps this true when the scope is bypassed).
        $contact = Contacts::where('uuid', $data['agenda_contact_id'] ?? null)
            ->where('iam_user_id', UserHelper::me()->id)
            ->first();

        if (!$contact) {
            throw (new ModelNotFoundException())->setModel(Contacts::class, [$data['agenda_contact_id'] ?? null]);
        }

        $account = Accounts::where('uuid', $data['crm_account_id'])->first();

        if (!$account) {
            throw (new ModelNotFoundException())->setModel(Accounts::class, [$data['crm_account_id']]);
        }

        $alreadyLinked = AccountContacts::withoutGlobalScope(AuthorizationScope::class)
            ->where('crm_account_id', $account->id)
            ->where('agenda_contact_id', $contact->id)
            ->exists();

        if ($alreadyLinked) {
            throw ValidationException::withMessages(['agenda_contact_id' => 'This contact is already linked to this account.']);
        }

        $data['visibility'] = $data['visibility'] ?? AccountContacts::VISIBILITY_PRIVATE;
        $data['is_primary'] = (bool) ($data['is_primary'] ?? false);

        return DB::transaction(function () use ($data, $account) {
            if ($data['is_primary']) {
                self::clearPrimary($account->id);
            }

            return parent::create($data);
        });
    }

    /**
     * Updates role, primary flag, visibility and data. Only the owner may update a link; the account and the contact of
     * a link never change.
     */
    public static function update($id, array $data)
    {
        unset($data['iam_user_id'], $data['iam_account_id'], $data['crm_account_id'], $data['agenda_contact_id']);

        $model = self::findOwned($id);

        return DB::transaction(function () use ($model, $data) {
            if (!empty($data['is_primary'])) {
                self::clearPrimary($model->crm_account_id, $model->id);
            }

            $model->update($data);

            return $model->fresh();
        });
    }

    /** Removes the link (and the shares of it). The Agenda contact stays. Only the owner may do this. */
    public static function delete($id)
    {
        $model = self::findOwned($id);

        return DB::transaction(function () use ($model) {
            // Access ends for everybody, including users the link was shared with.
            foreach (AccountContactShares::withoutGlobalScope(AuthorizationScope::class)
                ->where('crm_account_contact_id', $model->id)->get() as $share) {
                $share->delete();
            }

            return $model->delete();
        });
    }

    /**
     * Gives the caller an own copy of a contact reached through a link they can see. The copy is a new Agenda contact
     * owned by the caller (in their first address book) and is linked to the same CRM account as private link, so
     * the caller can edit it freely without touching the original.
     */
    public static function copy($id)
    {
        $link = self::getByRef($id);

        if (!$link) {
            throw (new ModelNotFoundException())->setModel(AccountContacts::class, [$id]);
        }

        if ((int) $link->iam_user_id === (int) UserHelper::me()->id) {
            throw new NotAllowedException('This contact is already yours.');
        }

        $contact = Contacts::withoutGlobalScope(AuthorizationScope::class)->where('id', $link->agenda_contact_id)->first();
        $addressBook = AddressBooks::first(); // Scoped: the caller's own address book

        if (!$contact || !$addressBook) {
            throw new NotAllowedException('You need an address book to copy a contact.');
        }

        $account = Accounts::where('id', $link->crm_account_id)->first();

        if (!$account) {
            throw (new ModelNotFoundException())->setModel(Accounts::class, [$link->crm_account_id]);
        }

        return DB::transaction(function () use ($contact, $addressBook, $link, $account) {
            $copy = ContactsService::create(
                collect($contact->only([
                    'name', 'surname', 'email', 'home_phone', 'cell_phone', 'fax_number',
                    'email_work', 'website', 'notes', 'description',
                ]))->merge(['agenda_address_book_id' => $addressBook->uuid])->all()
            );

            return self::create([
                'crm_account_id'    => $account->uuid,
                'agenda_contact_id' => $copy->uuid,
                'role'              => $link->role,
                'visibility'        => AccountContacts::VISIBILITY_PRIVATE,
            ]);
        });
    }

    /**
     * Creates the Agenda contact and links it to the CRM account in one step ("add a new contact from the account
     * page"). $contact takes the Agenda contact fields (agenda_address_book_id optional: first address book),
     * $link the link fields (crm_account_id required).
     */
    public static function createWithContact(array $contact, array $link)
    {
        TenantHelper::assertVisible(Accounts::class, $link['crm_account_id'] ?? null);

        if (empty($contact['agenda_address_book_id'])) {
            $addressBook = AddressBooks::first();

            if (!$addressBook) {
                throw new NotAllowedException('You need an address book to add a contact.');
            }

            $contact['agenda_address_book_id'] = $addressBook->uuid;
        }

        return DB::transaction(function () use ($contact, $link) {
            $created = ContactsService::create($contact);

            return self::create(array_merge($link, ['agenda_contact_id' => $created->uuid]));
        });
    }

    /** The caller's own link by uuid, or NotAllowedException (same message for unknown and foreign links). */
    public static function findOwned($uuid): AccountContacts
    {
        $model = AccountContacts::withoutGlobalScope(AuthorizationScope::class)
            ->where('uuid', $uuid)
            ->where('iam_user_id', UserHelper::me()->id)
            ->first();

        if (!$model) {
            throw new NotAllowedException(
                'We cannot find the related object. Maybe you are not the owner of this contact?'
            );
        }

        return $model;
    }

    /**
     * Only one primary link per (account, owner): clears the flag on the owner's other links of the account. Plain
     * query update on purpose: it only flips the flag, the observers must not run for the other rows.
     */
    private static function clearPrimary(int $crmAccountId, ?int $exceptId = null): void
    {
        $query = AccountContacts::withoutGlobalScope(AuthorizationScope::class)
            ->where('crm_account_id', $crmAccountId)
            ->where('iam_user_id', UserHelper::me()->id)
            ->where('is_primary', true);

        if ($exceptId !== null) {
            $query->where('id', '!=', $exceptId);
        }

        $query->update(['is_primary' => false]);
    }
}
