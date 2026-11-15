<?php

namespace NextDeveloper\CRM\Tests\Feature\Perspectives;

use NextDeveloper\CRM\Authorization\Roles\SalesAdminRole;
use NextDeveloper\CRM\Authorization\Roles\SalesManagerRole;
use NextDeveloper\CRM\Authorization\Roles\SalesPersonRole;
use NextDeveloper\CRM\Services\AccountsService as CrmAccountsService;
use NextDeveloper\IAM\Helpers\UserHelper;
use NextDeveloper\IAM\Services\AccountsService;
use NextDeveloper\IAM\Services\RolesService;
use NextDeveloper\IAM\Services\UsersService;

/**
 * Two tenants (A and B), each with a sales admin, a sales manager and a sales person, plus a platform account.
 * Every test acts as one of those users with the role check ON (the base TestCase bypass is switched off), and reads
 * through the real global scope stack (AuthorizationScope of the role + TenantScope).
 *
 * Data per tenant X (ids are integers, uuids kept for service calls):
 *   crmAccount[X]       customer record the tenant owns and its manager + person manage
 *   crmAccountOther[X]  second customer record of the tenant that nobody manages
 *   opportunity[X]['person']   opportunity of the sales person
 *   opportunity[X]['manager']  opportunity of the sales manager
 *   target[X]           contact list
 * plus the "forged" rows an attacker of tenant A could have ended up with (see forgeCrossTenantRowsFor()).
 */
trait TwoTenantFixture
{
    protected array $user = [];       // [tenant][role] => Users
    protected array $account = [];    // [tenant] => iam Accounts
    protected array $crmAccount = [];
    protected array $crmAccountOther = [];
    protected array $opportunity = [];
    protected array $target = [];
    protected $platformUser;
    protected $platformAccount;

    protected function bootTwoTenants(): void
    {
        // tests/TestCase turns the role bypass on globally; the bypass marks a caller as trusted, so switch it off
        $bypass = new \ReflectionProperty(UserHelper::class, 'isBypassRolesCheck');
        $bypass->setAccessible(true);
        $bypass->setValue(null, false);

        $this->platformUser = UsersService::createWithEmail(fake()->unique()->safeEmail());
        $this->platformAccount = AccountsService::createInitialAccount($this->platformUser);

        config([
            'crm.tenant_isolation.enabled' => true,
            'crm.tenant_isolation.platform_account_id' => $this->platformAccount->id,
        ]);

        foreach (['A', 'B'] as $tenant) {
            $this->buildTenant($tenant);
        }
    }

    private function buildTenant(string $tenant): void
    {
        $admin = UsersService::createWithEmail(fake()->unique()->safeEmail());
        $account = AccountsService::createInitialAccount($admin);
        $this->account[$tenant] = $account;

        // users created while acting inside the tenant become its members
        $this->actAs($admin, $account);
        $manager = UsersService::createWithEmail(fake()->unique()->safeEmail());
        $person = UsersService::createWithEmail(fake()->unique()->safeEmail());

        RolesService::assignUserToRole($admin, RolesService::getRole(new SalesAdminRole()), $account);
        RolesService::assignUserToRole($manager, RolesService::getRole(new SalesManagerRole()), $account);
        RolesService::assignUserToRole($person, RolesService::getRole(new SalesPersonRole()), $account);

        $this->user[$tenant] = ['admin' => $admin, 'manager' => $manager, 'person' => $person];

        // customers are created while acting as the platform (so the tenant does not become a member)
        $this->actAs($this->platformUser, $this->platformAccount);
        $customer = AccountsService::createInitialAccount(UsersService::createWithEmail(fake()->unique()->safeEmail()));
        $customerOther = AccountsService::createInitialAccount(UsersService::createWithEmail(fake()->unique()->safeEmail()));

        $this->crmAccount[$tenant] = $this->crmRecord($customer, $account->id);
        $this->crmAccountOther[$tenant] = $this->crmRecord($customerOther, $account->id);

        // the manager and the person manage the first record only
        foreach (['manager', 'person'] as $who) {
            \DB::table('crm_account_managers')->insert([
                'crm_account_id' => $this->crmAccount[$tenant]['id'],
                'iam_user_id' => $this->user[$tenant][$who]->id,
                'iam_account_id' => $account->id,
            ]);
        }

        foreach (['person', 'manager'] as $who) {
            $this->opportunity[$tenant][$who] = $this->opportunityRow(
                $account->id, $this->user[$tenant][$who]->id, $this->crmAccount[$tenant]['id'], "opp-$tenant-$who"
            );
        }

        $this->target[$tenant] = $this->targetRow($account->id, $admin->id, "target-$tenant");
    }

    /** CRM record of a customer for the given owning tenant; returns ['id' =>, 'uuid' =>, 'iam_account_id' =>]. */
    protected function crmRecord($customer, int $ownerId): array
    {
        $record = UserHelper::runAsAdmin(fn () => CrmAccountsService::create([
            'iam_account_id' => $customer->uuid,
            'owner_iam_account_id' => $ownerId,
            'is_created_by_owner' => true,
        ]));

        return ['id' => $record->id, 'uuid' => $record->uuid, 'iam_account_id' => $customer->id];
    }

    protected function opportunityRow(int $accountId, int $userId, int $crmAccountId, string $name): array
    {
        $uuid = (string) \Illuminate\Support\Str::uuid();
        $id = \DB::table('crm_opportunities')->insertGetId([
            'uuid' => $uuid, 'name' => $name, 'iam_account_id' => $accountId, 'iam_user_id' => $userId,
            'crm_account_id' => $crmAccountId,
        ]);

        return ['id' => $id, 'uuid' => $uuid];
    }

    protected function targetRow(int $accountId, int $userId, string $name): array
    {
        $uuid = (string) \Illuminate\Support\Str::uuid();
        $id = \DB::table('crm_targets')->insertGetId([
            'uuid' => $uuid, 'name' => $name, 'type' => 'user', 'iam_account_id' => $accountId, 'iam_user_id' => $userId,
        ]);

        return ['id' => $id, 'uuid' => $uuid];
    }

    /**
     * Rows that must NOT make tenant A's users see tenant B's data, however the role filter reads them:
     *  - a manager row that points B's customer record at one of A's users (the old WatchAccount bug wrote these)
     *  - an opportunity of B that carries one of A's users as iam_user_id (the "own rows" fallback of the roles)
     * Returns ['crmAccountId' =>, 'opportunity' => [...]] .
     */
    protected function forgeCrossTenantRowsFor(string $victimTenant, string $attackerTenant, string $who): array
    {
        $attacker = $this->user[$attackerTenant][$who];

        \DB::table('crm_account_managers')->insert([
            'crm_account_id' => $this->crmAccount[$victimTenant]['id'],
            'iam_user_id' => $attacker->id,
            'iam_account_id' => $this->account[$attackerTenant]->id,
        ]);

        $opportunity = $this->opportunityRow(
            $this->account[$victimTenant]->id, $attacker->id, $this->crmAccount[$victimTenant]['id'], "forged-$victimTenant-$who"
        );

        return ['crmAccountId' => $this->crmAccount[$victimTenant]['id'], 'opportunity' => $opportunity];
    }

    protected function actAs($user, $account): void
    {
        UserHelper::setUserById($user->id);
        UserHelper::setCurrentAccountById($account->id);
    }

    protected function actAsRole(string $tenant, string $role): void
    {
        $this->actAs($this->user[$tenant][$role], $this->account[$tenant]);
    }

    /** True when the current user can read the row through the model's normal scopes. */
    protected function sees(string $modelClass, int $id): bool
    {
        return $modelClass::where('id', $id)->exists();
    }
}
