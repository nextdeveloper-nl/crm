<?php

namespace NextDeveloper\CRM\Tests\Feature\Perspectives;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\CRM\Database\Models\AccountsPerspective;
use NextDeveloper\CRM\Database\Models\Opportunities;
use NextDeveloper\CRM\Database\Models\Targets;
use NextDeveloper\CRM\Helpers\TenantHelper;
use NextDeveloper\CRM\Services\AccountsService as CrmAccountsService;
use NextDeveloper\CRM\Services\OpportunitiesService;
use Tests\TestCase;

/**
 * Sales person: the narrowest role. Own opportunities, only accounts it manages. Security first: a person must never
 * reach a colleague's opportunity, another tenant's data, or change what it does not own.
 */
class SalesPersonPerspectiveTest extends TestCase
{
    use DatabaseTransactions;
    use TwoTenantFixture;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootTwoTenants();
    }

    // ---------------------------------------------------------------- security: isolation

    public function test_person_never_reads_another_tenants_records(): void
    {
        $this->actAsRole('A', 'person');

        $this->assertFalse($this->sees(Accounts::class, $this->crmAccount['B']['id']));
        $this->assertFalse($this->sees(AccountsPerspective::class, $this->crmAccount['B']['id']));
        $this->assertFalse($this->sees(Opportunities::class, $this->opportunity['B']['person']['id']));
        $this->assertFalse($this->sees(Targets::class, $this->target['B']['id']));
    }

    public function test_forged_rows_naming_the_person_do_not_open_the_other_tenant(): void
    {
        // an opportunity of B with iam_user_id = A's person, and a manager row for B's customer naming A's person:
        // both satisfy the role's own "mine" filter
        $forged = $this->forgeCrossTenantRowsFor('B', 'A', 'person');
        $this->actAsRole('A', 'person');

        $this->assertFalse($this->sees(Opportunities::class, $forged['opportunity']['id']));
        $this->assertFalse($this->sees(Accounts::class, $forged['crmAccountId']));
        $this->assertFalse($this->sees(AccountsPerspective::class, $forged['crmAccountId']));
    }

    public function test_person_cannot_read_a_colleagues_opportunity(): void
    {
        $this->actAsRole('A', 'person');

        $this->assertFalse($this->sees(Opportunities::class, $this->opportunity['A']['manager']['id']));
    }

    public function test_person_cannot_modify_or_delete_what_it_does_not_own(): void
    {
        $this->actAsRole('A', 'person');

        $foreign = $this->opportunity['B']['person'];
        $colleague = $this->opportunity['A']['manager'];
        $beforeForeign = \DB::table('crm_opportunities')->where('id', $foreign['id'])->first();
        $beforeColleague = \DB::table('crm_opportunities')->where('id', $colleague['id'])->first();
        $beforeAccount = \DB::table('crm_accounts')->where('id', $this->crmAccount['B']['id'])->first();

        foreach ([
            fn () => OpportunitiesService::update($foreign['uuid'], ['name' => 'taken over']),
            fn () => OpportunitiesService::delete($foreign['uuid']),
            fn () => OpportunitiesService::update($colleague['uuid'], ['name' => 'taken over']),
            fn () => CrmAccountsService::update($this->crmAccount['B']['uuid'], ['risk_level' => 'high']),
            fn () => CrmAccountsService::delete($this->crmAccount['B']['uuid']),
        ] as $attempt) {
            try {
                $attempt();
            } catch (\Throwable $e) {
                // refused: fine
            }
        }

        $this->assertEquals($beforeForeign, \DB::table('crm_opportunities')->where('id', $foreign['id'])->first());
        $this->assertEquals($beforeColleague, \DB::table('crm_opportunities')->where('id', $colleague['id'])->first());
        $this->assertEquals($beforeAccount, \DB::table('crm_accounts')->where('id', $this->crmAccount['B']['id'])->first());
    }

    public function test_person_cannot_create_a_record_for_a_foreign_customer_or_choose_the_owner(): void
    {
        $this->actAsRole('A', 'person');
        $foreignCustomerUuid = \DB::table('iam_accounts')->where('id', $this->crmAccount['B']['iam_account_id'])->value('uuid');

        $this->expectException(\Throwable::class);
        CrmAccountsService::create(['iam_account_id' => $foreignCustomerUuid, 'owner_iam_account_id' => $this->account['A']->id]);
    }

    public function test_person_cannot_write_the_shared_catalog_or_read_platform_wide_views(): void
    {
        $this->actAsRole('A', 'person');

        $this->assertFalse(TenantHelper::maySharedCatalogBeWritten());
        $this->assertSame(0, \NextDeveloper\CRM\Database\Models\DailyNewAccountsPerformance::query()->count());
    }

    public function test_person_cannot_find_another_tenants_target_for_linking(): void
    {
        $this->actAsRole('A', 'person');

        $this->assertNull(TenantHelper::findOwned(Targets::class, $this->target['B']['uuid']));
    }

    // ---------------------------------------------------------------- functional: own scope

    public function test_person_sees_its_own_opportunity_and_the_account_it_manages(): void
    {
        $this->actAsRole('A', 'person');

        $this->assertTrue($this->sees(Opportunities::class, $this->opportunity['A']['person']['id']));
        $this->assertTrue($this->sees(Accounts::class, $this->crmAccount['A']['id']));
    }

    public function test_person_does_not_see_accounts_it_does_not_manage(): void
    {
        $this->actAsRole('A', 'person');

        $this->assertFalse($this->sees(Accounts::class, $this->crmAccountOther['A']['id']));
    }
}
