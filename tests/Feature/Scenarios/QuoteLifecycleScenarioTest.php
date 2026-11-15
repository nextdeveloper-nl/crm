<?php

namespace NextDeveloper\CRM\Tests\Feature\Scenarios;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use NextDeveloper\CRM\Actions\Opportunities\ChangeOwner;
use NextDeveloper\CRM\Actions\Quotes\Approve;
use NextDeveloper\CRM\Actions\Quotes\RequestApproveQuote;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\CRM\Database\Models\AccountsPerspective;
use NextDeveloper\CRM\Database\Models\Calls;
use NextDeveloper\CRM\Database\Models\Meetings;
use NextDeveloper\CRM\Database\Models\Notes;
use NextDeveloper\CRM\Database\Models\Opportunities;
use NextDeveloper\CRM\Database\Models\OpportunitiesPerspective;
use NextDeveloper\CRM\Database\Models\Projects;
use NextDeveloper\CRM\Database\Models\QuoteItems;
use NextDeveloper\CRM\Database\Models\QuoteItemsPerspective;
use NextDeveloper\CRM\Database\Models\Quotes;
use NextDeveloper\CRM\Database\Models\QuotesPerspective;
use NextDeveloper\CRM\Services\AccountManagersService;
use NextDeveloper\CRM\Services\CallsService;
use NextDeveloper\CRM\Services\MeetingsService;
use NextDeveloper\CRM\Services\NotesService;
use NextDeveloper\CRM\Services\OpportunitiesService;
use NextDeveloper\CRM\Services\ProjectsService;
use NextDeveloper\CRM\Services\QuoteItemsService;
use NextDeveloper\CRM\Services\QuotesService;
use NextDeveloper\CRM\Tests\Feature\Perspectives\TwoTenantFixture;
use Tests\TestCase;

/**
 * Real-life scenario, run through the same services the controllers call, with the role check ON.
 *
 *  tenant A:  sales manager X (A.manager) and sales person A (A.person)
 *  tenant B:  sales manager Y (B.manager), the attacker
 *
 *  1. X creates a customer account
 *  2. X creates an opportunity on it and assigns it to A
 *  3. A logs a call, a meeting, a note and a project on the account
 *  4. A creates a quote on the opportunity and adds products
 *  5. A discounts an item and asks for approval
 *  meanwhile Y tries to read, change, hijack and plant data in everything X and A built.
 *
 * A workflow step that fails is recorded as a gap (an endpoint or permission that is missing for the real flow)
 * instead of aborting, so one run lists everything; the gap test reports them.
 */
class QuoteLifecycleScenarioTest extends TestCase
{
    use DatabaseTransactions;
    use TwoTenantFixture;

    private array $gaps = [];
    private array $world = [];

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootTwoTenants();
    }

    private function step(string $label, callable $fn)
    {
        try {
            // nested transaction = savepoint: a failing SQL statement must not abort the whole test transaction
            return \DB::transaction($fn);
        } catch (\Throwable $e) {
            $this->gaps[] = $label . ' -> ' . get_class($e) . ': ' . substr(preg_replace('/\s+/', ' ', $e->getMessage()), 0, 700)
                . ' | denial: ' . json_encode(\NextDeveloper\IAM\Helpers\UserHelper::getLastDenial());

            return null;
        }
    }

    private function X() { $this->actAsRole('A', 'manager'); }
    private function A() { $this->actAsRole('A', 'person'); }
    private function Y() { $this->actAsRole('B', 'manager'); }

    private function runWorkflow(): void
    {
        $w = &$this->world;

        // ---- 1. X creates an account (the customer company)
        $this->X();
        $name = 'Acme Scenario ' . uniqid();
        $accountTypeUuid = \DB::table('iam_account_types')->value('uuid');
        $w['account'] = $this->step('1 manager creates an account', fn () =>
            \App\Services\CRM\AccountsService::store([
                'name' => $name, 'domain_name' => uniqid() . '-scenario.example.com', 'iam_account_type_id' => $accountTypeUuid,
            ])
        );
        $w['crmAccount'] = $w['account']
            ? \DB::table('crm_accounts')->where('iam_account_id', $w['account']->iam_account_id)
                ->where('owner_iam_account_id', $this->account['A']->id)->first()
            : null;

        // ---- 2. X creates an opportunity and assigns it to A
        $w['opportunity'] = $w['crmAccount'] ? $this->step('2a manager creates an opportunity', fn () =>
            OpportunitiesService::create([
                'name' => 'Big deal ' . uniqid(), 'type' => 'sales', 'crm_account_id' => $w['crmAccount']->uuid,
            ])->fresh()
        ) : null;

        if ($w['opportunity']) {
            $this->step('2b manager makes the person a manager of the account', fn () =>
                \DB::table('crm_account_managers')->insert([
                    'crm_account_id' => $w['crmAccount']->id, 'iam_user_id' => $this->user['A']['person']->id,
                    'iam_account_id' => $this->account['A']->id,
                ]) || true
            );
            $this->step('2c manager assigns the opportunity to the person (ChangeOwner)', function () use ($w) {
                (new ChangeOwner($w['opportunity'], ['iam_user_id' => $this->user['A']['person']->uuid]))->handle();
            });
        }

        // ---- 3. A logs activity
        $this->A();
        if ($w['crmAccount']) {
            $w['call'] = $this->step('3a person logs a call', fn () => CallsService::create([
                'name' => 'Intro call', 'description' => 'secret pricing talk', 'crm_account_id' => $w['crmAccount']->uuid,
            ]));
            $w['meeting'] = $this->step('3b person logs a meeting', fn () => MeetingsService::create([
                'meeting_note' => 'secret roadmap', 'outcome' => 'positive', 'crm_account_id' => $w['crmAccount']->uuid,
            ]));
            $w['note'] = $this->step('3c person writes a note', fn () => NotesService::create([
                'note' => 'competitor discount is 30%', 'crm_account_id' => $w['crmAccount']->uuid,
            ]));
            $w['project'] = $this->step('3d person creates a project', fn () => ProjectsService::create([
                'name' => 'Rollout', 'url' => 'https://example.com/acme', 'crm_account_id' => $w['crmAccount']->uuid,
            ]));
        }

        // ---- 4. A creates a quote and adds products
        $w['quote'] = $w['opportunity'] ? $this->step('4a person creates a quote', fn () => QuotesService::create([
            'name' => 'Quote ' . uniqid(), 'crm_opportunity_id' => $w['opportunity']->uuid,
        ])->fresh()) : null;

        $product = \DB::table('marketplace_products')->first();
        $catalog = $product ? \DB::table('marketplace_product_catalogs')->first() : null;
        $w['hasProducts'] = $product && $catalog;

        if ($w['quote'] && $w['hasProducts']) {
            $w['item'] = $this->step('4b person adds a product to the quote', fn () => QuoteItemsService::create([
                'crm_quote_id' => $w['quote']->uuid, 'marketplace_product_id' => $product->uuid,
                'marketplace_product_catalog_id' => $catalog->uuid, 'quantity' => 3, 'unit_price' => 100,
            ])->fresh());
            $w['item2'] = $this->step('4c person adds a second product', fn () => QuoteItemsService::create([
                'crm_quote_id' => $w['quote']->uuid, 'marketplace_product_id' => $product->uuid,
                'marketplace_product_catalog_id' => $catalog->uuid, 'quantity' => 1, 'unit_price' => 50,
            ])->fresh());
        }

        // ---- 5. A discounts and asks for approval
        if ($w['item'] ?? null) {
            $this->step('5a person discounts an item', fn () => QuoteItemsService::update($w['item']->uuid, ['discount' => 10]));
        }
        if ($w['quote']) {
            $this->step('5b person asks for approval (RequestApproveQuote)', function () use ($w) {
                $before = \DB::table('crm_quotes')->where('id', $w['quote']->id)->value('approval_level');
                (new RequestApproveQuote($w['quote']->fresh()))->handle();
                $after = \DB::table('crm_quotes')->where('id', $w['quote']->id)->value('approval_level');

                if ($after !== 'pending-approval') {
                    throw new \RuntimeException("approval request did not move the quote: before=$before after=$after");
                }
            });
        }
    }

    /**
     * Builds the world once (the workflow is slow: events, notifications) and runs every check against it. All
     * failures are collected and reported together, so a single run shows every hole.
     */
    public function test_the_whole_scenario(): void
    {
        $this->runWorkflow();

        $failures = [];

        foreach ((new \ReflectionClass($this))->getMethods(\ReflectionMethod::IS_PRIVATE) as $m) {
            if (!str_starts_with($m->getName(), 'check_')) {
                continue;
            }

            // every check starts from the same world: its attacks are rolled back afterwards
            \DB::beginTransaction();
            try {
                $this->{$m->getName()}();
            } catch (\Throwable $e) {
                $failures[] = '[' . substr($m->getName(), 6) . '] ' . substr($e->getMessage(), 0, 400);
            } finally {
                \DB::rollBack();
            }
        }

        $this->assertSame([], $failures, "scenario findings:\n" . implode("\n", array_map(fn ($f) => ' - ' . $f, $failures)));
    }

    /** Runs an attack inside a savepoint; true when it went through, false when it was refused. */
    private function attempt(callable $fn): bool
    {
        try {
            \DB::transaction($fn);

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function snapshot(string $table, int $id): ?object
    {
        return \DB::table($table)->where('id', $id)->first();
    }

    // ================================================================ the workflow itself

    private function check_every_step_of_the_real_workflow_works(): void
    {
        $this->assertSame([], $this->gaps, "workflow gaps:\n - " . implode("\n - ", $this->gaps));
    }

    private function check_the_workflow_ends_in_the_expected_state(): void
    {
        $w = $this->world;
        $this->assertNotNull($w['crmAccount'], 'account was not created for tenant A');
        $this->assertTrue((bool) $w['crmAccount']->is_created_by_owner);

        $this->assertSame((int) $this->user['A']['person']->id, (int) $this->snapshot('crm_opportunities', $w['opportunity']->id)->iam_user_id, 'opportunity was not assigned to the person');
        $this->assertSame('pending-approval', $this->snapshot('crm_quotes', $w['quote']->id)->approval_level);

        // everything A created belongs to tenant A's account and to A
        foreach (['crm_calls' => 'call', 'crm_meetings' => 'meeting', 'crm_notes' => 'note', 'crm_projects' => 'project'] as $table => $key) {
            $row = $w[$key] ?? null;
            $this->assertNotNull($row, "$key missing");
            $this->assertSame((int) $this->account['A']->id, (int) \DB::table($table)->where('uuid', $row->uuid)->value('iam_account_id'), "$table has the wrong tenant");
        }
    }

    private function check_the_sales_person_and_the_manager_see_the_work_they_should(): void
    {
        $w = $this->world;

        $this->A();
        $this->assertTrue($this->sees(Opportunities::class, $w['opportunity']->id));
        $this->assertTrue($this->sees(Quotes::class, $w['quote']->id), 'the person cannot see its own quote');
        $this->assertTrue($this->sees(Calls::class, $w['call']->id));

        $this->X();
        $this->assertTrue($this->sees(Accounts::class, $w['crmAccount']->id));
        $this->assertTrue($this->sees(Quotes::class, $w['quote']->id), 'the manager cannot see the quote of its person');
    }

    // ================================================================ Y tries to steal

    private function check_Y_cannot_read_anything_X_and_A_built(): void
    {
        $w = $this->world;
        $this->Y();

        $leaks = [];
        $check = function (string $label, string $class, $row) use (&$leaks) {
            if ($row && $this->sees($class, $row->id)) {
                $leaks[] = $label;
            }
        };

        $check('crm_accounts', Accounts::class, $w['crmAccount']);
        $check('accounts_perspective', AccountsPerspective::class, $w['crmAccount']);
        $check('opportunities', Opportunities::class, $w['opportunity']);
        $check('opportunities_perspective', OpportunitiesPerspective::class, $w['opportunity']);
        $check('calls', Calls::class, $w['call'] ?? null);
        $check('meetings', Meetings::class, $w['meeting'] ?? null);
        $check('notes', Notes::class, $w['note'] ?? null);
        $check('projects', Projects::class, $w['project'] ?? null);
        $check('quotes', Quotes::class, $w['quote']);
        $check('quotes_perspective', QuotesPerspective::class, $w['quote']);
        $check('quote_items', QuoteItems::class, $w['item'] ?? null);
        $check('quote_items_perspective', QuoteItemsPerspective::class, $w['item'] ?? null);

        // the customer's iam_account itself
        if ($w['account']) {
            if (\NextDeveloper\IAM\Database\Models\Accounts::where('id', $w['account']->iam_account_id)->exists()) {
                $leaks[] = 'iam_accounts (the customer company)';
            }
        }

        $this->assertSame([], $leaks, 'Y can read: ' . implode(', ', $leaks));
    }

    private function check_Y_cannot_find_them_by_uuid_or_through_lists(): void
    {
        $w = $this->world;
        $this->Y();

        $this->assertNull(Opportunities::where('uuid', $w['opportunity']->uuid)->first());
        $this->assertNull(Quotes::where('uuid', $w['quote']->uuid)->first());
        $this->assertNull(Accounts::where('uuid', $w['crmAccount']->uuid)->first());

        // list endpoints (index) must not contain them either
        $this->assertFalse(Opportunities::query()->get()->contains('id', $w['opportunity']->id));
        $this->assertFalse(Quotes::query()->get()->contains('id', $w['quote']->id));
        $this->assertFalse(Notes::query()->get()->contains('id', ($w['note'] ?? null)?->id));
    }

    private function check_Y_cannot_change_or_delete_what_X_and_A_built(): void
    {
        $w = $this->world;
        $tracked = [
            'crm_opportunities' => $w['opportunity']->id, 'crm_quotes' => $w['quote']->id, 'crm_accounts' => $w['crmAccount']->id,
            'crm_calls' => $w['call']->id ?? 0, 'crm_notes' => $w['note']->id ?? 0, 'crm_meetings' => $w['meeting']->id ?? 0,
            'crm_projects' => $w['project']->id ?? 0, 'crm_quote_items' => $w['item']->id ?? 0,
        ];
        $before = array_map(fn ($id, $t) => $this->snapshot($t, $id), $tracked, array_keys($tracked));

        $this->Y();
        foreach ([
            fn () => OpportunitiesService::update($w['opportunity']->uuid, ['name' => 'stolen', 'iam_user_id' => $this->user['B']['manager']->id]),
            fn () => OpportunitiesService::delete($w['opportunity']->uuid),
            fn () => QuotesService::update($w['quote']->uuid, ['name' => 'stolen', 'approval_level' => 'approved']),
            fn () => QuotesService::delete($w['quote']->uuid),
            fn () => \NextDeveloper\CRM\Services\AccountsService::update($w['crmAccount']->uuid, ['risk_level' => 'high']),
            fn () => \NextDeveloper\CRM\Services\AccountsService::delete($w['crmAccount']->uuid),
            fn () => CallsService::update($w['call']->uuid, ['description' => 'defaced']),
            fn () => NotesService::update($w['note']->uuid, ['note' => 'defaced']),
            fn () => MeetingsService::update($w['meeting']->uuid, ['outcome' => 'defaced']),
            fn () => ProjectsService::update($w['project']->uuid, ['name' => 'defaced']),
            fn () => ProjectsService::delete($w['project']->uuid),
            fn () => QuoteItemsService::update($w['item']->uuid, ['discount' => 99, 'unit_price' => 0]),
            fn () => QuoteItemsService::delete($w['item']->uuid),
        ] as $attempt) {
            $this->attempt($attempt);
        }

        $after = array_map(fn ($id, $t) => $this->snapshot($t, $id), $tracked, array_keys($tracked));
        $this->assertEquals($before, $after, 'Y changed or deleted something that belongs to tenant A');
    }

    private function check_Y_cannot_plant_data_into_As_account_or_quote(): void
    {
        $w = $this->world;
        $counts = fn () => [
            'calls' => \DB::table('crm_calls')->where('crm_account_id', $w['crmAccount']->id)->count(),
            'notes' => \DB::table('crm_notes')->where('crm_account_id', $w['crmAccount']->id)->count(),
            'meetings' => \DB::table('crm_meetings')->where('crm_account_id', $w['crmAccount']->id)->count(),
            'projects' => \DB::table('crm_projects')->where('crm_account_id', $w['crmAccount']->id)->count(),
            'opportunities' => \DB::table('crm_opportunities')->where('crm_account_id', $w['crmAccount']->id)->count(),
            'managers' => \DB::table('crm_account_managers')->where('crm_account_id', $w['crmAccount']->id)->count(),
            'quotes' => \DB::table('crm_quotes')->where('crm_opportunity_id', $w['opportunity']->id)->count(),
            'items' => \DB::table('crm_quote_items')->where('crm_quote_id', $w['quote']->id)->count(),
        ];
        $before = $counts();

        $this->Y();
        $product = \DB::table('marketplace_products')->first();
        $catalog = \DB::table('marketplace_product_catalogs')->first();

        foreach ([
            fn () => CallsService::create(['name' => 'spam', 'description' => 'x', 'crm_account_id' => $w['crmAccount']->uuid]),
            fn () => NotesService::create(['note' => 'spam', 'crm_account_id' => $w['crmAccount']->uuid]),
            fn () => MeetingsService::create(['meeting_note' => 'x', 'outcome' => 'x', 'crm_account_id' => $w['crmAccount']->uuid]),
            fn () => ProjectsService::create(['name' => 'spam', 'url' => 'https://example.com', 'crm_account_id' => $w['crmAccount']->uuid]),
            fn () => OpportunitiesService::create(['name' => 'spam', 'type' => 'sales', 'crm_account_id' => $w['crmAccount']->uuid]),
            fn () => AccountManagersService::create(['crm_account_id' => $w['crmAccount']->uuid, 'iam_user_id' => $this->user['B']['manager']->uuid]),
            fn () => QuotesService::create(['name' => 'spam', 'crm_opportunity_id' => $w['opportunity']->uuid]),
            fn () => $product ? QuoteItemsService::create([
                'crm_quote_id' => $w['quote']->uuid, 'marketplace_product_id' => $product->uuid,
                'marketplace_product_catalog_id' => $catalog->uuid, 'quantity' => 1, 'unit_price' => 1,
            ]) : null,
        ] as $attempt) {
            $this->attempt($attempt);
        }

        $this->assertSame($before, $counts(), 'Y planted rows into tenant A\'s account/quote');
    }

    private function check_Y_cannot_hijack_the_opportunity_or_approve_the_quote_through_actions(): void
    {
        $w = $this->world;
        $ownerBefore = $this->snapshot('crm_opportunities', $w['opportunity']->id)->iam_user_id;
        $approvalBefore = $this->snapshot('crm_quotes', $w['quote']->id)->approval_level;

        $this->Y();
        foreach ([
            fn () => (new ChangeOwner($w['opportunity'], ['iam_user_id' => $this->user['B']['manager']->uuid]))->handle(),
            fn () => (new Approve($w['quote']->fresh()))->handle(),
            fn () => (new RequestApproveQuote($w['quote']->fresh()))->handle(),
        ] as $attempt) {
            $this->attempt($attempt);
        }

        $this->assertSame($ownerBefore, $this->snapshot('crm_opportunities', $w['opportunity']->id)->iam_user_id, 'Y took over the opportunity');
        $this->assertSame($approvalBefore, $this->snapshot('crm_quotes', $w['quote']->id)->approval_level, 'Y changed the approval state');
    }

    // ================================================================ insider abuse inside tenant A

    private function check_Y_cannot_re_parent_its_own_records_onto_Xs_account(): void
    {
        $w = $this->world;
        $this->Y();

        $ownAccount = $this->crmAccount['B'];
        $call = CallsService::create(['name' => 'own call', 'description' => 'x', 'crm_account_id' => $ownAccount['uuid']]);
        $note = NotesService::create(['note' => 'own note', 'crm_account_id' => $ownAccount['uuid']]);
        $ownOpportunity = $this->opportunity['B']['manager'];

        // moving own records onto X's account would put Y's data into X's timeline (and expose X's account id as an oracle)
        $this->attempt(fn () => CallsService::update($call->uuid, ['crm_account_id' => $w['crmAccount']->uuid]));
        $this->attempt(fn () => NotesService::update($note->uuid, ['crm_account_id' => $w['crmAccount']->uuid]));
        $this->attempt(fn () => OpportunitiesService::update($ownOpportunity['uuid'], ['crm_account_id' => $w['crmAccount']->uuid]));

        $this->assertSame((int) $ownAccount['id'], (int) \DB::table('crm_calls')->where('uuid', $call->uuid)->value('crm_account_id'), 'a call was re-parented onto another tenant');
        $this->assertSame((int) $ownAccount['id'], (int) \DB::table('crm_notes')->where('uuid', $note->uuid)->value('crm_account_id'), 'a note was re-parented onto another tenant');
        $this->assertSame((int) $ownAccount['id'], (int) \DB::table('crm_opportunities')->where('id', $ownOpportunity['id'])->value('crm_account_id'), 'an opportunity was re-parented onto another tenant');
    }

    private function check_the_manager_cannot_hand_the_opportunity_to_a_user_of_another_company(): void
    {
        $w = $this->world;
        $this->X();

        $this->attempt(fn () => (new ChangeOwner($w['opportunity'], ['iam_user_id' => $this->user['B']['person']->uuid]))->handle());

        $this->assertSame(
            (int) $this->user['A']['person']->id,
            (int) $this->snapshot('crm_opportunities', $w['opportunity']->id)->iam_user_id,
            'the opportunity (and its trade secrets) was handed to a user outside tenant A'
        );
    }

    private function check_the_sales_person_cannot_approve_its_own_discount(): void
    {
        $w = $this->world;
        $this->A();

        // the discount asked in step 5 is pending; the person must not be able to move it to approved
        foreach ([
            fn () => QuotesService::update($w['quote']->uuid, ['approval_level' => 'approved']),
            fn () => (new Approve($w['quote']->fresh()))->handle(),
        ] as $attempt) {
            $this->attempt($attempt);
        }

        $this->assertNotSame(
            'approved',
            $this->snapshot('crm_quotes', $w['quote']->id)->approval_level,
            'a sales person approved its own discount'
        );
    }

    private function check_the_sales_person_cannot_skip_validation_by_converting_the_quote_to_an_invoice(): void
    {
        $w = $this->world;
        $this->A();

        $converted = $this->attempt(fn () => QuotesService::convertToInvoice($w['quote']->fresh()));

        $this->assertFalse($converted, 'an unapproved quote was converted to an invoice');
    }

    private function check_raising_the_discount_after_asking_for_approval_sends_the_quote_back_to_draft(): void
    {
        $w = $this->world;
        $this->A();

        $this->assertSame('pending-approval', $this->snapshot('crm_quotes', $w['quote']->id)->approval_level, 'precondition: quote is pending');

        $this->attempt(fn () => QuoteItemsService::update($w['item']->uuid, ['discount' => 90]));

        // the approver validated the OLD numbers: a changed quote must not stay "pending" or "approved"
        $this->assertSame(
            'draft',
            $this->snapshot('crm_quotes', $w['quote']->id)->approval_level,
            'the discount was raised after the quote went to approval and the approval state was kept'
        );
    }

    private function check_the_person_cannot_touch_the_managers_other_opportunity(): void
    {
        $this->A();
        $other = $this->opportunity['A']['manager'];   // fixture opportunity owned by the manager
        $before = $this->snapshot('crm_opportunities', $other['id']);

        $this->attempt(fn () => OpportunitiesService::update($other['uuid'], ['name' => 'taken']));

        $this->assertEquals($before, $this->snapshot('crm_opportunities', $other['id']));
    }
}
