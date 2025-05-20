<?php

namespace Helpers;

use NextDeveloper\CRM\Database\Models\Targets;
use NextDeveloper\CRM\Database\Models\TargetUsers;
use NextDeveloper\CRM\Database\Models\Users;

class TargetHelper
{
    public static function addUserToTarget(Targets $target, Users $user) : TargetUsers {
        $targetUsers = TargetUsers::withoutGlobalScopes()
            ->where('crm_target_id', $target->id)
            ->where('crm_user_id', $user->id)
            ->first();

        if(!$targetUsers) {
            $targetUsers = TargetUsers::create([
                'crm_target_id' => $target->id,
                'crm_user_id' => $user->id
            ]);
        }

        return $targetUsers;
    }
}
