<?php

namespace NextDeveloper\CRM\Tests\Feature\Perspectives;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\CRM\Database\Models\AccountsPerspective;
use NextDeveloper\CRM\Database\Models\Industries;
use NextDeveloper\CRM\Database\Models\Opportunities;
use NextDeveloper\CRM\Database\Models\Targets;
use NextDeveloper\CRM\Helpers\TenantHelper;
use NextDeveloper\CRM\Services\AccountsService as CrmAccountsService;
use Tests\TestCase;

/**
 * Sales admin ("sees everything" role): the role applies no account filter on most tables, so the tenant boundary
 * (TenantScope) is the only thing between tenant A's admin and tenant B's data. Security first.
 */
class SalesAdminPerspectiveTest extends TestCase
{
    use DatabaseTransactions;
    use TwoTenantFixture;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootTwoTenants();
    }

    // ---------------------------------------------------------------- security: isolation

    public function test_admin_never_reads_another_tenants_records(): void
    {
        $this->actAsRole('A', 'admin');

        $this->assertFalse($this->sees(Accounts::class, $this->crmAccount['B']['id']));
        $this->assertFalse($this->sees(AccountsPerspective::class, $this->crmAccount['B']['id']));
        $this->assertFalse($this->sees(Opportunities::class, $this->opportunity['B']['person']['id']));
        $this->assertFalse($this->sees(Opportunities::class, $this->opportunity['B']['manager']['id']));
        $this->assertFalse($this->sees(Targets::class, $this->target['B']['id']));
    }

    public function test_admin_does_not_find_another_tenants_records_by_uuid_either(): void
    {
        $this->actAsRole('A', 'admin');

        $this->assertNull(Accounts::where('uuid', $this->crmAccount['B']['uuid'])->first());
        $this->assertNull(Opportunities::where('uuid', $this->opportunity['B']['person']['uuid'])->first());
        $this->assertNull(TenantHelper::findOwned(Targets::class, $this->target['B']['uuid']));
    }

    public function test_admin_cannot_list_the_platform_trigger_records_or_platform_wide_views(): void
    {
        $this->actAsRole('A', 'admin');

        // records the iam_accounts trigger created (owner 5, the platform tenant) are invisible to a tenant
        $platformOwned = \DB::table('crm_accounts')->where('owner_iam_account_id', 5)->value('id');
        $this->assertNotNull($platformOwned);
        $this->assertFalse($this->sees(Accounts::class, (int) $platformOwned));

        // platform-only aggregates return nothing for a tenant
        $this->assertSame(0, \NextDeveloper\CRM\Database\Models\DailyNewAccountsPerformance::query()->count());
        $this->assertSame(0, \NextDeveloper\CRM\Database\Models\MonthlyPayingCustomersPerformance::query()->count());
    }

    public function test_admin_cannot_modify_another_tenants_record(): void
    {
        $this->actAsRole('A', 'admin');

        $before = \DB::table('crm_accounts')->where('id', $this->crmAccount['B']['id'])->first();

        try {
            CrmAccountsService::update($this->crmAccount['B']['uuid'], ['risk_level' => 'high', 'is_paying_customer' => true]);
        } catch (\Throwable $e) {
            // refused (not found / not allowed): fine
        }

        $after = \DB::table('crm_accounts')->where('id', $this->crmAccount['B']['id'])->first();
        $this->assertEquals($before, $after, "tenant A's admin changed tenant B's CRM record");
    }

    public function test_admin_cannot_delete_another_tenants_record(): void
    {
        $this->actAsRole('A', 'admin');

        try {
            CrmAccountsService::delete($this->crmAccount['B']['uuid']);
        } catch (\Throwable $e) {
            // refused: fine
        }

        $this->assertNull(\DB::table('crm_accounts')->where('id', $this->crmAccount['B']['id'])->value('deleted_at'));
    }

    public function test_admin_cannot_create_a_record_for_a_foreign_customer_or_choose_the_owner(): void
    {
        $this->actAsRole('A', 'admin');

        // pointing a CRM record at somebody else's iam_account is refused
        $foreignCustomerUuid = \DB::table('iam_accounts')->where('id', $this->crmAccount['B']['iam_account_id'])->value('uuid');

        $this->expectException(\NextDeveloper\Commons\Exceptions\NotAllowedException::class);
        CrmAccountsService::create(['iam_account_id' => $foreignCustomerUuid, 'owner_iam_account_id' => $this->account['A']->id]);
    }

    public function test_admin_cannot_claim_ownership_or_repoint_a_customer_through_update(): void
    {
        $this->actAsRole('A', 'admin');

        $row = $this->crmAccount['A'];
        $foreignCustomerId = $this->crmAccount['B']['iam_account_id'];

        try {
            CrmAccountsService::update($row['uuid'], [
                'owner_iam_account_id' => $this->account['B']->id,
                'iam_account_id' => $foreignCustomerId,
                'is_created_by_owner' => false,
            ]);
        } catch (\Throwable $e) {
            // either way the columns below must be unchanged
        }

        $after = \DB::table('crm_accounts')->where('id', $row['id'])->first();
        $this->assertSame((int) $this->account['A']->id, (int) $after->owner_iam_account_id);
        $this->assertSame((int) $row['iam_account_id'], (int) $after->iam_account_id);
        $this->assertTrue((bool) $after->is_created_by_owner);
    }

    public function test_admin_cannot_write_the_shared_catalog(): void
    {
        $this->actAsRole('A', 'admin');

        $this->assertFalse(TenantHelper::maySharedCatalogBeWritten());

        $before = Industries::query()->count();
        try {
            Industries::create(['name' => 'tenant-made-' . uniqid()]);
        } catch (\Throwable $e) {
            // refused by the model guard (or an earlier role check)
        }
        $this->assertSame($before, Industries::query()->count());
    }

    public function test_forged_manager_and_user_rows_do_not_open_the_other_tenant(): void
    {
        $forged = $this->forgeCrossTenantRowsFor('B', 'A', 'admin');
        $this->actAsRole('A', 'admin');

        $this->assertFalse($this->sees(Accounts::class, $forged['crmAccountId']));
        $this->assertFalse($this->sees(Opportunities::class, $forged['opportunity']['id']));
    }

    // ---------------------------------------------------------------- functional: own data

    public function test_admin_sees_every_record_of_its_own_tenant(): void
    {
        $this->actAsRole('A', 'admin');

        $this->assertTrue($this->sees(Accounts::class, $this->crmAccount['A']['id']));
        $this->assertTrue($this->sees(Accounts::class, $this->crmAccountOther['A']['id']), 'admin sees records nobody manages');
        $this->assertTrue($this->sees(AccountsPerspective::class, $this->crmAccount['A']['id']));
        $this->assertTrue($this->sees(Opportunities::class, $this->opportunity['A']['person']['id']));
        $this->assertTrue($this->sees(Opportunities::class, $this->opportunity['A']['manager']['id']));
        $this->assertTrue($this->sees(Targets::class, $this->target['A']['id']));
    }

    public function test_platform_account_is_the_only_one_that_sees_across_tenants(): void
    {
        $this->actAs($this->platformUser, $this->platformAccount);

        $this->assertTrue(TenantHelper::isPlatformAccount((int) $this->platformAccount->id));
        $this->assertTrue(TenantHelper::maySharedCatalogBeWritten());
    }
}
