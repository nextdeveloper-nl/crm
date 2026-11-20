<?php

namespace NextDeveloper\CRM\Tests\Feature\AccountContacts;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use NextDeveloper\Agenda\Database\Models\Contacts;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\CRM\Database\Models\AccountContacts;
use NextDeveloper\CRM\Services\AccountContactSharesService;
use NextDeveloper\CRM\Services\AccountContactsService;
use NextDeveloper\CRM\Tests\Feature\Perspectives\TwoTenantFixture;
use Tests\TestCase;

/**
 * Contacts of a CRM account: an Agenda contact (private to its owner) linked to a CRM account, with the link deciding
 * who else sees it (private | team | specific), and the platform users of the account listed next to them.
 *
 * Runs with the role check ON through the real scope stack, on the two-tenant fixture (tenant A and B, each with an
 * admin, a manager and a person; the manager and the person manage crmAccount[X] but not crmAccountOther[X]).
 *
 * Needs the tables of schemas/changes/2026_11_20_crm_account_contacts.sql.
 */
class AccountContactsTest extends TestCase
{
    use DatabaseTransactions;
    use TwoTenantFixture;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootTwoTenants();
    }

    // ---------------------------------------------------------------- helpers

    /** Agenda contact (and its address book) owned by the given user; returns ['id' =>, 'uuid' =>]. */
    private function makeContact(string $tenant, string $who, ?string $email = null): array
    {
        $now = now();
        $owner = ['iam_user_id' => $this->user[$tenant][$who]->id, 'iam_account_id' => $this->account[$tenant]->id];

        $bookId = \DB::table('agenda_address_books')->insertGetId([
            'uuid' => (string) Str::uuid(), 'name' => "book-$tenant-$who", 'created_at' => $now, 'updated_at' => $now,
        ] + $owner);

        $uuid = (string) Str::uuid();
        $id = \DB::table('agenda_contacts')->insertGetId([
            'uuid' => $uuid, 'name' => 'Jane', 'surname' => "Doe-$tenant-$who", 'email' => $email,
            'agenda_address_book_id' => $bookId, 'created_at' => $now, 'updated_at' => $now,
        ] + $owner);

        return ['id' => $id, 'uuid' => $uuid];
    }

    /** Links a contact as the given user and returns the link. */
    private function link(string $tenant, string $who, array $contact, array $extra = []): AccountContacts
    {
        $this->actAsRole($tenant, $who);

        return AccountContactsService::create($extra + [
            'crm_account_id'    => $this->crmAccount[$tenant]['uuid'],
            'agenda_contact_id' => $contact['uuid'],
        ]);
    }

    private function visibleUuids(string $tenant, string $who): array
    {
        $this->actAsRole($tenant, $who);

        return AccountContactsService::get()->pluck('uuid')->all();
    }

    // ---------------------------------------------------------------- linking

    public function test_owner_links_own_contact_and_it_is_private_by_default(): void
    {
        $link = $this->link('A', 'person', $this->makeContact('A', 'person'), ['role' => 'CTO']);

        $this->assertSame(AccountContacts::VISIBILITY_PRIVATE, $link->visibility);
        $this->assertSame('CTO', $link->role);
        $this->assertSame($this->user['A']['person']->id, (int) $link->iam_user_id);
        $this->assertSame($this->account['A']->id, (int) $link->iam_account_id);
    }

    public function test_owner_and_account_come_from_the_acting_user_not_the_payload(): void
    {
        $link = $this->link('A', 'person', $this->makeContact('A', 'person'), [
            'iam_user_id'    => $this->user['A']['manager']->uuid,
            'iam_account_id' => $this->account['B']->uuid,
        ]);

        $this->assertSame($this->user['A']['person']->id, (int) $link->iam_user_id);
        $this->assertSame($this->account['A']->id, (int) $link->iam_account_id);
    }

    public function test_cannot_link_a_contact_owned_by_someone_else(): void
    {
        $colleaguesContact = $this->makeContact('A', 'manager');
        $this->actAsRole('A', 'person');

        $this->expectException(ModelNotFoundException::class);

        AccountContactsService::create([
            'crm_account_id'    => $this->crmAccount['A']['uuid'],
            'agenda_contact_id' => $colleaguesContact['uuid'],
        ]);
    }

    public function test_cannot_link_to_another_tenants_account(): void
    {
        $contact = $this->makeContact('A', 'person');
        $this->actAsRole('A', 'person');

        $this->expectException(ModelNotFoundException::class);

        AccountContactsService::create([
            'crm_account_id'    => $this->crmAccount['B']['uuid'],
            'agenda_contact_id' => $contact['uuid'],
        ]);
    }

    public function test_cannot_link_to_an_account_the_user_does_not_manage(): void
    {
        $contact = $this->makeContact('A', 'person');
        $this->actAsRole('A', 'person');

        $this->expectException(ModelNotFoundException::class);

        AccountContactsService::create([
            'crm_account_id'    => $this->crmAccountOther['A']['uuid'],
            'agenda_contact_id' => $contact['uuid'],
        ]);
    }

    public function test_same_contact_cannot_be_linked_twice_to_an_account(): void
    {
        $contact = $this->makeContact('A', 'person');
        $this->link('A', 'person', $contact);

        $this->expectException(ValidationException::class);

        $this->link('A', 'person', $contact);
    }

    public function test_only_one_primary_per_account_and_owner(): void
    {
        $first = $this->link('A', 'person', $this->makeContact('A', 'person'), ['is_primary' => true]);
        $second = $this->link('A', 'person', $this->makeContact('A', 'person'), ['is_primary' => true]);

        $this->assertFalse($first->fresh()->is_primary);
        $this->assertTrue($second->fresh()->is_primary);

        // switching back through update clears the other one again
        AccountContactsService::update($first->uuid, ['is_primary' => true]);

        $this->assertTrue($first->fresh()->is_primary);
        $this->assertFalse($second->fresh()->is_primary);
    }

    public function test_primary_of_one_owner_does_not_touch_the_primary_of_a_colleague(): void
    {
        $mine = $this->link('A', 'person', $this->makeContact('A', 'person'), ['is_primary' => true]);
        $theirs = $this->link('A', 'manager', $this->makeContact('A', 'manager'), ['is_primary' => true]);

        $this->assertTrue($mine->fresh()->is_primary);
        $this->assertTrue($theirs->fresh()->is_primary);
    }

    // ---------------------------------------------------------------- visibility

    public function test_private_link_is_invisible_to_colleagues(): void
    {
        $link = $this->link('A', 'person', $this->makeContact('A', 'person'));

        $this->assertContains($link->uuid, $this->visibleUuids('A', 'person'));
        $this->assertNotContains($link->uuid, $this->visibleUuids('A', 'manager'));
        $this->assertNotContains($link->uuid, $this->visibleUuids('A', 'admin'));
        $this->assertNull(AccountContactsService::getByRef($link->uuid));
    }

    public function test_team_link_is_visible_to_colleagues_who_can_see_the_account(): void
    {
        $link = $this->link('A', 'person', $this->makeContact('A', 'person'), ['visibility' => 'team']);

        $this->assertContains($link->uuid, $this->visibleUuids('A', 'manager'));

        $this->actAsRole('A', 'manager');
        $this->assertNotNull(AccountContactsService::getByRef($link->uuid));
    }

    public function test_team_link_on_an_account_the_viewer_cannot_see_stays_hidden(): void
    {
        // owner (admin) sees crmAccountOther[A]; the manager does not manage it
        $contact = $this->makeContact('A', 'admin');
        $this->actAsRole('A', 'admin');
        $link = AccountContactsService::create([
            'crm_account_id'    => $this->crmAccountOther['A']['uuid'],
            'agenda_contact_id' => $contact['uuid'],
            'visibility'        => 'team',
        ]);

        $this->assertNotContains($link->uuid, $this->visibleUuids('A', 'manager'));
    }

    public function test_team_link_never_reaches_another_tenant(): void
    {
        $link = $this->link('A', 'person', $this->makeContact('A', 'person'), ['visibility' => 'team']);

        foreach (['admin', 'manager', 'person'] as $who) {
            $this->assertNotContains($link->uuid, $this->visibleUuids('B', $who));
        }
    }

    public function test_specific_link_is_visible_only_to_the_shared_user(): void
    {
        $link = $this->link('A', 'person', $this->makeContact('A', 'person'), ['visibility' => 'specific']);

        $this->actAsRole('A', 'person');
        AccountContactSharesService::create([
            'crm_account_contact_id' => $link->uuid,
            'shared_iam_user_id'     => $this->user['A']['manager']->uuid,
        ]);

        $this->assertContains($link->uuid, $this->visibleUuids('A', 'manager'));
        $this->assertNotContains($link->uuid, $this->visibleUuids('A', 'admin'));
    }

    public function test_a_revoked_share_stops_giving_access(): void
    {
        $link = $this->link('A', 'person', $this->makeContact('A', 'person'), ['visibility' => 'specific']);

        $this->actAsRole('A', 'person');
        $share = AccountContactSharesService::create([
            'crm_account_contact_id' => $link->uuid,
            'shared_iam_user_id'     => $this->user['A']['manager']->uuid,
        ]);
        $this->assertContains($link->uuid, $this->visibleUuids('A', 'manager'));

        $this->actAsRole('A', 'person');
        AccountContactSharesService::delete($share->uuid);

        $this->assertNotContains($link->uuid, $this->visibleUuids('A', 'manager'));
    }

    public function test_a_share_is_ignored_while_the_link_is_not_specific(): void
    {
        $link = $this->link('A', 'person', $this->makeContact('A', 'person'), ['visibility' => 'specific']);

        $this->actAsRole('A', 'person');
        AccountContactSharesService::create([
            'crm_account_contact_id' => $link->uuid,
            'shared_iam_user_id'     => $this->user['A']['manager']->uuid,
        ]);
        AccountContactsService::update($link->uuid, ['visibility' => 'private']);

        $this->assertNotContains($link->uuid, $this->visibleUuids('A', 'manager'));
    }

    public function test_cannot_share_with_a_user_of_another_tenant(): void
    {
        $link = $this->link('A', 'person', $this->makeContact('A', 'person'), ['visibility' => 'specific']);

        $this->actAsRole('A', 'person');

        $this->expectException(ModelNotFoundException::class);

        AccountContactSharesService::create([
            'crm_account_contact_id' => $link->uuid,
            'shared_iam_user_id'     => $this->user['B']['person']->uuid,
        ]);
    }

    // ---------------------------------------------------------------- only the owner writes

    public function test_non_owner_cannot_update_delete_or_share_a_visible_link(): void
    {
        $link = $this->link('A', 'person', $this->makeContact('A', 'person'), ['visibility' => 'team']);

        $this->actAsRole('A', 'manager');

        foreach ([
            fn () => AccountContactsService::update($link->uuid, ['role' => 'hijacked']),
            fn () => AccountContactsService::delete($link->uuid),
            fn () => AccountContactSharesService::create([
                'crm_account_contact_id' => $link->uuid,
                'shared_iam_user_id'     => $this->user['A']['admin']->uuid,
            ]),
        ] as $attempt) {
            try {
                $attempt();
                $this->fail('A non owner changed a contact link.');
            } catch (NotAllowedException $e) {
                $this->assertTrue(true);
            }
        }

        $this->assertNull($link->fresh()->role);
        $this->assertNotNull(AccountContacts::withoutGlobalScopes()->find($link->id));
    }

    public function test_account_and_contact_of_a_link_cannot_be_changed(): void
    {
        $contact = $this->makeContact('A', 'person');
        $link = $this->link('A', 'person', $contact);

        $this->actAsRole('A', 'person');
        AccountContactsService::update($link->uuid, [
            'crm_account_id'    => $this->crmAccountOther['A']['uuid'],
            'agenda_contact_id' => $this->makeContact('A', 'person')['uuid'],
            'role'              => 'CEO',
        ]);

        $fresh = $link->fresh();
        $this->assertSame((int) $this->crmAccount['A']['id'], (int) $fresh->crm_account_id);
        $this->assertSame((int) $contact['id'], (int) $fresh->agenda_contact_id);
        $this->assertSame('CEO', $fresh->role);
    }

    public function test_deleting_a_link_removes_its_shares_but_keeps_the_contact(): void
    {
        $contact = $this->makeContact('A', 'person');
        $link = $this->link('A', 'person', $contact, ['visibility' => 'specific']);

        $this->actAsRole('A', 'person');
        AccountContactSharesService::create([
            'crm_account_contact_id' => $link->uuid,
            'shared_iam_user_id'     => $this->user['A']['manager']->uuid,
        ]);
        AccountContactsService::delete($link->uuid);

        $this->assertNotContains($link->uuid, $this->visibleUuids('A', 'manager'));
        $this->assertNotNull(Contacts::withoutGlobalScopes()->find($contact['id']));
    }

    // ---------------------------------------------------------------- sub objects

    public function test_sub_objects_follow_visibility_and_an_allowlist(): void
    {
        $link = $this->link('A', 'person', $this->makeContact('A', 'person'));

        $this->actAsRole('A', 'manager');
        try {
            AccountContactsService::relatedObjects($link->uuid, 'states');
            $this->fail('A private link was reachable through its sub objects.');
        } catch (ModelNotFoundException $e) {
            $this->assertTrue(true);
        }

        $this->actAsRole('A', 'person');
        $this->assertNotNull(AccountContactsService::relatedObjects($link->uuid, 'states'));

        // anything outside the allowlist is refused, tags included
        foreach (['agenda_contact_id', 'tags'] as $notAllowed) {
            try {
                AccountContactsService::relatedObjects($link->uuid, $notAllowed);
                $this->fail("Sub object $notAllowed was served.");
            } catch (NotAllowedException $e) {
                $this->assertTrue(true);
            }
        }
    }

    public function test_contact_links_have_no_actions(): void
    {
        $link = $this->link('A', 'person', $this->makeContact('A', 'person'));

        $this->expectException(NotAllowedException::class);

        AccountContactsService::doAction($link->uuid, 'anything');
    }

    // ---------------------------------------------------------------- copy

    public function test_a_colleague_copies_a_team_contact_into_an_own_private_link(): void
    {
        $original = $this->makeContact('A', 'person', 'jane@example.com');
        $link = $this->link('A', 'person', $original, ['visibility' => 'team', 'role' => 'CTO']);

        // the manager needs an address book to receive the copy
        $this->makeContact('A', 'manager');
        $this->actAsRole('A', 'manager');

        $copy = AccountContactsService::copy($link->uuid);

        $this->assertSame($this->user['A']['manager']->id, (int) $copy->iam_user_id);
        $this->assertSame(AccountContacts::VISIBILITY_PRIVATE, $copy->visibility);
        $this->assertSame('CTO', $copy->role);
        $this->assertNotSame((int) $original['id'], (int) $copy->agenda_contact_id);
        $this->assertSame(
            'jane@example.com',
            Contacts::withoutGlobalScopes()->find($copy->agenda_contact_id)->email
        );
    }

    public function test_cannot_copy_an_invisible_or_own_contact(): void
    {
        $link = $this->link('A', 'person', $this->makeContact('A', 'person'));

        $this->actAsRole('A', 'manager');
        try {
            AccountContactsService::copy($link->uuid);
            $this->fail('A private contact was copied by a colleague.');
        } catch (ModelNotFoundException $e) {
            $this->assertTrue(true);
        }

        $this->actAsRole('A', 'person');
        $this->expectException(NotAllowedException::class);
        AccountContactsService::copy($link->uuid);
    }

    // ---------------------------------------------------------------- account users as contacts

    /** crm_users row for the platform user of a customer account, so it shows up in the users perspective. */
    private function makeAccountUser(string $tenant, string $position): array
    {
        $iamUserId = \DB::table('iam_account_user')
            ->where('iam_account_id', $this->crmAccount[$tenant]['iam_account_id'])
            ->value('iam_user_id');

        $uuid = (string) Str::uuid();
        \DB::table('crm_users')->insert(['uuid' => $uuid, 'iam_user_id' => $iamUserId, 'position' => $position]);

        return ['uuid' => $uuid, 'iam_user_id' => $iamUserId, 'email' => \DB::table('iam_users')->where('id', $iamUserId)->value('email')];
    }

    public function test_people_lists_agenda_contacts_and_account_users(): void
    {
        $accountUser = $this->makeAccountUser('A', 'VP Engineering');
        $link = $this->link('A', 'person', $this->makeContact('A', 'person'));

        $people = collect(AccountContactsService::people($this->crmAccount['A']['uuid']));

        $agenda = $people->firstWhere('id', $link->uuid);
        $user = $people->firstWhere('id', $accountUser['uuid']);

        $this->assertSame('agenda', $agenda['source']);
        $this->assertSame('account_user', $user['source']);
        $this->assertSame('VP Engineering', $user['role']);
        $this->assertFalse($user['is_owner']);
        $this->assertSame($accountUser['email'], $user['contact']['email']);
    }

    public function test_people_exposes_only_the_contact_subset_of_a_user(): void
    {
        $accountUser = $this->makeAccountUser('A', 'CTO');
        $this->actAsRole('A', 'person');

        $row = collect(AccountContactsService::people($this->crmAccount['A']['uuid']))->firstWhere('id', $accountUser['uuid']);

        foreach (['nin', 'birthday', 'hobbies', 'relationship_status', 'child_count', 'about'] as $private) {
            $this->assertArrayNotHasKey($private, $row);
            $this->assertArrayNotHasKey($private, $row['contact']);
        }
    }

    public function test_people_flags_an_agenda_contact_matching_an_account_user_by_email(): void
    {
        $accountUser = $this->makeAccountUser('A', 'CTO');
        $link = $this->link('A', 'person', $this->makeContact('A', 'person', strtoupper($accountUser['email'])));

        $people = collect(AccountContactsService::people($this->crmAccount['A']['uuid']));

        $this->assertSame($accountUser['uuid'], $people->firstWhere('id', $link->uuid)['matches_account_user_id']);
    }

    public function test_people_hides_private_contacts_of_colleagues_but_keeps_account_users(): void
    {
        $accountUser = $this->makeAccountUser('A', 'CTO');
        $private = $this->link('A', 'person', $this->makeContact('A', 'person'));

        $this->actAsRole('A', 'manager');
        $ids = collect(AccountContactsService::people($this->crmAccount['A']['uuid']))->pluck('id');

        $this->assertNotContains($private->uuid, $ids);
        $this->assertContains($accountUser['uuid'], $ids);
    }

    public function test_people_of_an_account_the_caller_cannot_see_is_a_404(): void
    {
        $this->makeAccountUser('B', 'CTO');

        foreach ([$this->crmAccount['B']['uuid'], $this->crmAccountOther['A']['uuid']] as $uuid) {
            $this->actAsRole('A', 'person');

            try {
                AccountContactsService::people($uuid);
                $this->fail("people() answered for an account the caller cannot see: $uuid");
            } catch (ModelNotFoundException $e) {
                $this->assertTrue(true);
            }
        }
    }

    public function test_copy_account_user_makes_a_private_own_contact_and_refuses_a_duplicate(): void
    {
        $accountUser = $this->makeAccountUser('A', 'CTO');
        $this->makeContact('A', 'person'); // address book for the copy
        $this->actAsRole('A', 'person');

        $link = AccountContactsService::copyAccountUser($accountUser['uuid']);

        $this->assertSame($this->user['A']['person']->id, (int) $link->iam_user_id);
        $this->assertSame(AccountContacts::VISIBILITY_PRIVATE, $link->visibility);
        $this->assertSame('CTO', $link->role);
        $this->assertSame(
            strtolower($accountUser['email']),
            strtolower(Contacts::withoutGlobalScopes()->find($link->agenda_contact_id)->email)
        );

        $this->expectException(ValidationException::class);
        AccountContactsService::copyAccountUser($accountUser['uuid']);
    }

    public function test_copy_account_user_of_another_tenant_is_refused(): void
    {
        $foreign = $this->makeAccountUser('B', 'CTO');
        $this->makeContact('A', 'person');
        $this->actAsRole('A', 'person');

        $this->expectException(ModelNotFoundException::class);

        AccountContactsService::copyAccountUser($foreign['uuid']);
    }
}
