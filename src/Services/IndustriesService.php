<?php

namespace NextDeveloper\CRM\Services;

use NextDeveloper\CRM\Database\Models\Industries;
use NextDeveloper\CRM\Services\AbstractServices\AbstractIndustriesService;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;

/**
 * This class is responsible from managing the data for Industries
 *
 * Class IndustriesService.
 *
 * @package NextDeveloper\CRM\Database\Models
 */
class IndustriesService extends AbstractIndustriesService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    public static function getIndustries() {
        return Industries::withoutGlobalScopes()->pluck('name')->toArray();
    }
}
