<?php

namespace NextDeveloper\CRM\Tests\Feature\Perspectives;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\CRM\Database\Models\AccountsPerspective;
use NextDeveloper\CRM\Database\Models\Opportunities;
use NextDeveloper\CRM\Database\Models\Targets;
use NextDeveloper\CRM\Helpers\TenantHelper;
use NextDeveloper\CRM\Services\AccountsService as CrmAccountsService;
use Tests\TestCase;

/**
 * Sales manager: sees the records of the accounts it manages and the tenant's opportunities, filtered by the role
 * through crm_account_managers / iam_user_id. Those role filters read rows an attacker can influence, so the tenant
 * boundary must hold regardless. Security first.
 */
class SalesManagerPerspectiveTest extends TestCase
{
    use DatabaseTransactions;
    use TwoTenantFixture;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootTwoTenants();
    }

    // ---------------------------------------------------------------- security: isolation

    public function test_manager_never_reads_another_tenants_records(): void
    {
        $this->actAsRole('A', 'manager');

        $this->assertFalse($this->sees(Accounts::class, $this->crmAccount['B']['id']));
        $this->assertFalse($this->sees(AccountsPerspective::class, $this->crmAccount['B']['id']));
        $this->assertFalse($this->sees(Opportunities::class, $this->opportunity['B']['manager']['id']));
        $this->assertFalse($this->sees(Opportunities::class, $this->opportunity['B']['person']['id']));
        $this->assertFalse($this->sees(Targets::class, $this->target['B']['id']));
    }

    public function test_forged_manager_row_does_not_expose_the_other_tenants_customer(): void
    {
        // a crm_account_managers row that links B's customer record to A's manager (iam_account_id = A) satisfies the
        // role's own filter; only the tenant boundary stops it
        $forged = $this->forgeCrossTenantRowsFor('B', 'A', 'manager');
        $this->actAsRole('A', 'manager');

        $this->assertFalse($this->sees(Accounts::class, $forged['crmAccountId']));
        $this->assertFalse($this->sees(AccountsPerspective::class, $forged['crmAccountId']));
    }

    public function test_forged_opportunity_carrying_the_managers_user_id_stays_invisible(): void
    {
        $forged = $this->forgeCrossTenantRowsFor('B', 'A', 'manager');
        $this->actAsRole('A', 'manager');

        $this->assertFalse($this->sees(Opportunities::class, $forged['opportunity']['id']));
    }

    public function test_manager_cannot_modify_or_delete_another_tenants_record(): void
    {
        $this->actAsRole('A', 'manager');

        $before = \DB::table('crm_accounts')->where('id', $this->crmAccount['B']['id'])->first();

        foreach ([
            fn () => CrmAccountsService::update($this->crmAccount['B']['uuid'], ['risk_level' => 'high']),
            fn () => CrmAccountsService::delete($this->crmAccount['B']['uuid']),
        ] as $attempt) {
            try {
                $attempt();
            } catch (\Throwable $e) {
                // refused: fine
            }
        }

        $this->assertEquals($before, \DB::table('crm_accounts')->where('id', $this->crmAccount['B']['id'])->first());
    }

    public function test_manager_cannot_create_a_record_for_a_foreign_customer(): void
    {
        $this->actAsRole('A', 'manager');
        $foreignCustomerUuid = \DB::table('iam_accounts')->where('id', $this->crmAccount['B']['iam_account_id'])->value('uuid');

        $this->expectException(\Throwable::class);
        CrmAccountsService::create(['iam_account_id' => $foreignCustomerUuid]);
    }

    public function test_manager_cannot_change_the_owner_of_its_own_tenants_record(): void
    {
        $this->actAsRole('A', 'manager');
        $row = $this->crmAccount['A'];

        try {
            CrmAccountsService::update($row['uuid'], ['owner_iam_account_id' => $this->account['B']->id]);
        } catch (\Throwable $e) {
        }

        $this->assertSame(
            (int) $this->account['A']->id,
            (int) \DB::table('crm_accounts')->where('id', $row['id'])->value('owner_iam_account_id')
        );
    }

    public function test_manager_cannot_write_the_shared_catalog(): void
    {
        $this->actAsRole('A', 'manager');

        $this->assertFalse(TenantHelper::maySharedCatalogBeWritten());
    }

    // ---------------------------------------------------------------- functional: own scope

    public function test_manager_sees_only_the_accounts_it_manages(): void
    {
        $this->actAsRole('A', 'manager');

        $this->assertTrue($this->sees(Accounts::class, $this->crmAccount['A']['id']));
        $this->assertFalse(
            $this->sees(Accounts::class, $this->crmAccountOther['A']['id']),
            'a record of the same tenant that the manager does not manage must stay hidden'
        );
    }

    public function test_manager_sees_the_opportunities_of_its_tenant(): void
    {
        $this->actAsRole('A', 'manager');

        $this->assertTrue($this->sees(Opportunities::class, $this->opportunity['A']['manager']['id']));
        $this->assertTrue($this->sees(Opportunities::class, $this->opportunity['A']['person']['id']));
    }
}
