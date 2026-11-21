<?php

namespace NextDeveloper\CRM\Tests\Feature\Perspectives;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use NextDeveloper\CRM\Services\OpportunitiesService;
use Tests\TestCase;

/**
 * Who may delete an opportunity: a sales manager deletes any opportunity of the team (current account),
 * a sales person only its own, and nobody reaches another tenant's opportunities.
 */
class OpportunityDeleteTest extends TestCase
{
    use DatabaseTransactions;
    use TwoTenantFixture;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bootTwoTenants();
    }

    /** True when the opportunity row is soft deleted or gone. */
    private function isDeleted(int $id): bool
    {
        $row = \DB::table('crm_opportunities')->where('id', $id)->first();

        return !$row || $row->deleted_at !== null;
    }

    private function tryDelete(string $uuid): void
    {
        try {
            OpportunitiesService::delete($uuid);
        } catch (\Throwable $e) {
            // refused: the assertions check the row
        }
    }

    public function test_manager_deletes_a_team_mates_opportunity(): void
    {
        $this->actAsRole('A', 'manager');
        $opp = $this->opportunity['A']['person'];

        $this->tryDelete($opp['uuid']);

        $this->assertTrue($this->isDeleted($opp['id']));
    }

    public function test_manager_deletes_its_own_opportunity(): void
    {
        $this->actAsRole('A', 'manager');
        $opp = $this->opportunity['A']['manager'];

        $this->tryDelete($opp['uuid']);

        $this->assertTrue($this->isDeleted($opp['id']));
    }

    public function test_manager_cannot_delete_another_tenants_opportunity(): void
    {
        $this->actAsRole('A', 'manager');
        $opp = $this->opportunity['B']['person'];

        $this->tryDelete($opp['uuid']);

        $this->assertFalse($this->isDeleted($opp['id']));
    }

    public function test_person_deletes_its_own_opportunity(): void
    {
        $this->actAsRole('A', 'person');
        $opp = $this->opportunity['A']['person'];

        $this->tryDelete($opp['uuid']);

        $this->assertTrue($this->isDeleted($opp['id']));
    }

    public function test_person_cannot_delete_a_team_mates_opportunity(): void
    {
        $this->actAsRole('A', 'person');
        $opp = $this->opportunity['A']['manager'];

        $this->tryDelete($opp['uuid']);

        $this->assertFalse($this->isDeleted($opp['id']));
    }

    public function test_person_cannot_delete_another_tenants_opportunity(): void
    {
        $this->actAsRole('A', 'person');
        $opp = $this->opportunity['B']['person'];

        $this->tryDelete($opp['uuid']);

        $this->assertFalse($this->isDeleted($opp['id']));
    }
}
