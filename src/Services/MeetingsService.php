<?php

namespace NextDeveloper\CRM\Services;

use NextDeveloper\CRM\Services\AbstractServices\AbstractMeetingsService;

/**
 * This class is responsible from managing the data for Meetings
 *
 * Class MeetingsService.
 *
 * @package NextDeveloper\CRM\Database\Models
 */
class MeetingsService extends AbstractMeetingsService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    /**
     * Multi-tenancy: the CRM account (and opportunity) a record is attached to must be visible to the caller;
     * otherwise a tenant could plant records into another tenant's account.
     */
    public static function create(array $data)
    {
        \NextDeveloper\CRM\Helpers\TenantHelper::assertVisible(\NextDeveloper\CRM\Database\Models\Accounts::class, $data['crm_account_id'] ?? null);
        \NextDeveloper\CRM\Helpers\TenantHelper::assertVisible(\NextDeveloper\CRM\Database\Models\Opportunities::class, $data['crm_opportunity_id'] ?? null);

        return parent::create($data);
    }
}