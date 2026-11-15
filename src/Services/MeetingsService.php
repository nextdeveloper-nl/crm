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
        return parent::create(\NextDeveloper\CRM\Helpers\TenantHelper::guardWrite($data));
    }

    /** Re-parenting onto an account / opportunity the caller cannot see is refused. */
    public static function update($id, array $data)
    {
        return parent::update($id, \NextDeveloper\CRM\Helpers\TenantHelper::guardWrite($data));
    }
}