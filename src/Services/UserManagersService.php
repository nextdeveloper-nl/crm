<?php

namespace NextDeveloper\CRM\Services;

use NextDeveloper\CRM\Database\Models\UserManagers;
use NextDeveloper\CRM\Services\AbstractServices\AbstractUserManagersService;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;

/**
 * This class is responsible from managing the data for UserManagers
 *
 * Class UserManagersService.
 *
 * @package NextDeveloper\CRM\Database\Models
 */
class UserManagersService extends AbstractUserManagersService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    public static function create($data)
    {
        $userManager = UserManagers::withoutGlobalScope(AuthorizationScope::class)
            ->where('crm_user_id', $data['crm_user_id'])
            ->where('iam_user_id', $data['iam_user_id'])
            ->first();

        if($userManager)
            return $userManager;

        return parent::create($data);
    }
}
