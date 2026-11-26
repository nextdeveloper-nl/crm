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
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\CRM\Database\Filters\AccountContactsQueryFilter;
use NextDeveloper\CRM\Database\Models\AccountContactShares;
use NextDeveloper\CRM\Database\Models\AccountContacts;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\CRM\Database\Models\UsersPerspective;
use NextDeveloper\CRM\Http\Transformers\AccountContactsTransformer;
use NextDeveloper\IAM\Database\Models\Users;
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

    /** Name of the address book that is created for a user who adds a first contact without having one. */
    public const DEFAULT_ADDRESS_BOOK_NAME = 'CRM contacts';

    /** Agenda contact fields a CRM user may set when a contact is created from a CRM account. */
    private const CONTACT_FIELDS = [
        'name', 'surname', 'email', 'home_phone', 'cell_phone', 'fax_number',
        'email_work', 'website', 'notes', 'description',
    ];

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
                                // Only the role scope is removed: soft deleted (revoked) shares must not grant access.
                                AccountContactShares::withoutGlobalScope(AuthorizationScope::class)
                                    ->whereNull('deleted_at')
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
     * Contact links serve no sub objects. The generic route is removed and this refuses anyway: the generated
     * implementation resolves the link without the visibility rules and calls whatever relation name it is given. The
     * tag and state relations of the platform traits do not match the database (common_tags.object_type and
     * common_states.model_type do not exist), so there is nothing to serve.
     */
    public static function relatedObjects($uuid, $object)
    {
        throw new NotAllowedException('This object has no sub objects.');
    }

    /** A contact link has no actions; refusing here keeps the generic action route from reaching the model. */
    public static function doAction($objectId, $action, ...$params)
    {
        throw new NotAllowedException('This object has no actions.');
    }

    /**
     * Links one of the caller's own Agenda contacts to a CRM account the caller can see. The owner, the owning account
     * and the visibility default always come from the acting user, never from the payload.
     */
    public static function create(array $data)
    {
        TenantHelper::assertVisible(Accounts::class, $data['crm_account_id'] ?? null);

        // Only the owner may link a contact. The Agenda role scope is not used (a sales user needs no Agenda role to
        // manage the contacts of a customer), the explicit owner + account filter of ownContactsQuery() replaces it.
        $contact = self::ownContactsQuery()->where('uuid', $data['agenda_contact_id'] ?? null)->first();

        if (!$contact) {
            throw (new ModelNotFoundException())->setModel(Contacts::class, [$data['agenda_contact_id'] ?? null]);
        }

        return self::linkContact($contact, $data);
    }

    /**
     * Links an Agenda contact that is already known to be the caller's own to a CRM account the caller can see.
     */
    private static function linkContact(Contacts $contact, array $data)
    {
        unset($data['iam_user_id'], $data['iam_account_id']);

        $account = Accounts::where('uuid', $data['crm_account_id'] ?? null)->first();

        if (!$account) {
            throw (new ModelNotFoundException())->setModel(Accounts::class, [$data['crm_account_id'] ?? null]);
        }

        $alreadyLinked = AccountContacts::withoutGlobalScope(AuthorizationScope::class)
            ->where('crm_account_id', $account->id)
            ->where('agenda_contact_id', $contact->id)
            ->exists();

        if ($alreadyLinked) {
            throw ValidationException::withMessages(['agenda_contact_id' => 'This contact is already linked to this account.']);
        }

        // The id, not the uuid: the generated service resolves a uuid through the Agenda role scope, which finds
        // nothing for a caller without an Agenda role.
        $data['agenda_contact_id'] = (int) $contact->id;
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

        if (!$contact) {
            throw (new ModelNotFoundException())->setModel(Contacts::class, [$link->agenda_contact_id]);
        }

        $account = Accounts::where('id', $link->crm_account_id)->first();

        if (!$account) {
            throw (new ModelNotFoundException())->setModel(Accounts::class, [$link->crm_account_id]);
        }

        return DB::transaction(function () use ($contact, $link, $account) {
            $copy = self::createOwnContact($contact->only(self::CONTACT_FIELDS), self::ownAddressBook());

            return self::linkContact($copy, [
                'crm_account_id'    => $account->uuid,
                'role'              => $link->role,
                'visibility'        => AccountContacts::VISIBILITY_PRIVATE,
            ]);
        });
    }

    /**
     * Creates the Agenda contact and links it to the CRM account in one step ("add a new contact from the account
     * page"). $contact takes the Agenda contact fields (agenda_address_book_id optional: see ownAddressBook()),
     * $link the link fields (crm_account_id required).
     */
    public static function createWithContact(array $contact, array $link)
    {
        TenantHelper::assertVisible(Accounts::class, $link['crm_account_id'] ?? null);

        return DB::transaction(function () use ($contact, $link) {
            $created = self::createOwnContact($contact, self::ownAddressBook($contact['agenda_address_book_id'] ?? null));

            return self::linkContact($created, $link);
        });
    }

    /**
     * Everybody of a CRM account the caller can talk to: the Agenda contacts linked to it that the caller may see
     * (source "agenda") and the people of the account itself, its platform users (source "account_user").
     *
     * Account users are read live from the users perspective, never copied, so they are always current and are
     * visible to exactly the callers who can already see that perspective (its role scope). Only a small contact
     * subset of the user is exposed here, not the whole perspective row.
     *
     * An agenda contact whose email matches an account user is flagged with matches_account_user_id so a client can
     * merge the two instead of listing the person twice.
     */
    public static function people(string $crmAccountUuid): array
    {
        // 404 for an account the caller cannot see (no probing of other tenants' accounts).
        TenantHelper::assertVisible(Accounts::class, $crmAccountUuid);

        $account = Accounts::where('uuid', $crmAccountUuid)->first();

        if (!$account) {
            throw (new ModelNotFoundException())->setModel(Accounts::class, [$crmAccountUuid]);
        }

        // Role scoped: a caller only gets the users of accounts they may see.
        $users = UsersPerspective::where('crm_account_id', $account->id)->get();
        $iamUserUuids = Users::whereIn('id', $users->pluck('iam_user_id'))->pluck('uuid', 'id');

        $accountUsers = $users->map(function ($user) use ($iamUserUuids, $account) {
            return [
                'source'         => 'account_user',
                'id'             => $user->uuid,                           // crm_users uuid
                'iam_user_id'    => $iamUserUuids[$user->iam_user_id] ?? null,
                'crm_account_id' => $account->uuid,
                'role'           => $user->position ?: $user->job,
                'is_primary'     => false,
                'is_evangelist'  => (bool) $user->is_evangelist,
                'is_owner'       => false,
                'contact'        => [
                    'id'         => null,
                    'name'       => $user->name,
                    'surname'    => $user->surname,
                    'email'      => $user->email,
                    'cell_phone' => $user->phone_number,
                    'city'       => $user->city,
                ],
            ];
        })->values();

        $emailToUser = $accountUsers
            ->filter(fn ($row) => !empty($row['contact']['email']))
            ->keyBy(fn ($row) => mb_strtolower($row['contact']['email']));

        $transformer = new AccountContactsTransformer();

        $contacts = self::visibleQuery()
            ->where('crm_account_id', $account->id)
            ->get()
            ->map(function (AccountContacts $link) use ($transformer, $emailToUser) {
                $row = $transformer->transform($link);
                $row['source'] = 'agenda';

                // Same person as an account user? (by either email of the contact)
                foreach ([$row['contact']['email'] ?? null, $row['contact']['email_work'] ?? null] as $email) {
                    if ($email && isset($emailToUser[mb_strtolower($email)])) {
                        $row['matches_account_user_id'] = $emailToUser[mb_strtolower($email)]['id'];
                        break;
                    }
                }

                return $row;
            })
            ->values();

        return $contacts->concat($accountUsers)->values()->all();
    }

    /**
     * Gives the caller an own Agenda contact made from an account user, linked privately to that user's CRM account,
     * so the caller can keep their own notes without touching the platform user. Only users the caller can see (users
     * perspective scope) can be copied.
     */
    public static function copyAccountUser(string $crmUserUuid)
    {
        $user = UsersPerspective::where('uuid', $crmUserUuid)->first();

        if (!$user) {
            throw (new ModelNotFoundException())->setModel(UsersPerspective::class, [$crmUserUuid]);
        }

        $account = Accounts::where('id', $user->crm_account_id)->first();

        if (!$account) {
            throw (new ModelNotFoundException())->setModel(Accounts::class, [$user->crm_account_id]);
        }

        // Do not create a second contact for the same person on the same account.
        if ($user->email) {
            $existing = self::ownContactsQuery()->whereIn('id', AccountContacts::withoutGlobalScope(AuthorizationScope::class)
                ->where('crm_account_id', $account->id)
                ->where('iam_user_id', UserHelper::me()->id)
                ->select('agenda_contact_id'))
                ->where(function ($query) use ($user) {
                    $query->whereRaw('lower(email) = ?', [mb_strtolower($user->email)])
                        ->orWhereRaw('lower(email_work) = ?', [mb_strtolower($user->email)]);
                })
                ->exists();

            if ($existing) {
                throw ValidationException::withMessages(['crm_user_id' => 'You already have this person as a contact of this account.']);
            }
        }

        return DB::transaction(function () use ($user, $account) {
            $contact = self::createOwnContact([
                'name'        => $user->name,
                'surname'     => $user->surname,
                'email'       => $user->email,
                'cell_phone'  => $user->phone_number,
                'description' => $user->job_description,
            ], self::ownAddressBook());

            return self::linkContact($contact, [
                'crm_account_id'    => $account->uuid,
                'role'              => $user->position ?: $user->job,
                'visibility'        => AccountContacts::VISIBILITY_PRIVATE,
            ]);
        });
    }

    /**
     * The caller's own Agenda contacts for the "link an existing contact" picker, newest first. $search matches name,
     * surname and both emails. Served here so a sales user does not need an Agenda role to pick a contact.
     *
     * @return array<int, array{id: string, name: ?string, surname: ?string, email: ?string, email_work: ?string, cell_phone: ?string}>
     */
    public static function ownAgendaContacts(?string $search = null, int $limit = 10): array
    {
        $query = self::ownContactsQuery();
        $search = trim((string) $search);

        if ($search !== '') {
            $like = '%' . addcslashes(mb_strtolower($search), '\\%_') . '%';

            $query->where(function (Builder $match) use ($like) {
                $match->whereRaw('lower(name) like ?', [$like])
                    ->orWhereRaw('lower(surname) like ?', [$like])
                    ->orWhereRaw("lower(concat_ws(' ', name, surname)) like ?", [$like])
                    ->orWhereRaw('lower(email) like ?', [$like])
                    ->orWhereRaw('lower(email_work) like ?', [$like]);
            });
        }

        return $query->orderByDesc('id')
            ->limit(max(1, min($limit, 50)))
            ->get()
            ->map(fn (Contacts $contact) => [
                'id'         => $contact->uuid,
                'name'       => $contact->name,
                'surname'    => $contact->surname,
                'email'      => $contact->email,
                'email_work' => $contact->email_work,
                'cell_phone' => $contact->cell_phone,
            ])
            ->all();
    }

    /**
     * Agenda contacts owned by the caller in the current account. The Agenda role scope is replaced by this explicit
     * owner + account filter: the role scope returns nothing for a user without an Agenda role, and CRM roles are
     * what authorize the contacts of a CRM account.
     */
    private static function ownContactsQuery(): Builder
    {
        return Contacts::withoutGlobalScope(AuthorizationScope::class)
            ->where('iam_user_id', UserHelper::me()->id)
            ->where('iam_account_id', UserHelper::currentAccount()->id);
    }

    /**
     * The address book new contacts of the caller go to: the given one (must be the caller's own), else the caller's
     * first address book, else a default one that is created on first use. Reading and creating it does not depend on
     * an Agenda role; the row always belongs to the acting user and the current account.
     */
    private static function ownAddressBook(?string $uuid = null): AddressBooks
    {
        $query = AddressBooks::withoutGlobalScope(AuthorizationScope::class)
            ->where('iam_user_id', UserHelper::me()->id)
            ->where('iam_account_id', UserHelper::currentAccount()->id);

        if ($uuid) {
            $addressBook = $query->where('uuid', $uuid)->first();

            if (!$addressBook) {
                throw (new ModelNotFoundException())->setModel(AddressBooks::class, [$uuid]);
            }

            return $addressBook;
        }

        return $query->orderBy('id')->first()
            ?? UserHelper::withRolesCheckBypassed(fn () => AddressBooks::create([
                'name'           => self::DEFAULT_ADDRESS_BOOK_NAME,
                'iam_user_id'    => UserHelper::me()->id,
                'iam_account_id' => UserHelper::currentAccount()->id,
            ]));
    }

    /**
     * Creates an Agenda contact owned by the caller in the given (own) address book. The caller is already authorized
     * by the CRM role (crm_account_contacts:create), so the Agenda write policy is bypassed for this single insert;
     * owner and account are always the acting user and the current account, never taken from the payload.
     */
    private static function createOwnContact(array $fields, AddressBooks $addressBook): Contacts
    {
        return UserHelper::withRolesCheckBypassed(fn () => Contacts::create(array_merge(
            collect($fields)->only(self::CONTACT_FIELDS)->all(),
            [
                'agenda_address_book_id' => $addressBook->id,
                'iam_user_id'            => UserHelper::me()->id,
                'iam_account_id'         => UserHelper::currentAccount()->id,
            ]
        )));
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
