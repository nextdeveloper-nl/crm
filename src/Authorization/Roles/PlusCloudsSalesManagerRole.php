<?php

namespace NextDeveloper\CRM\Authorization\Roles;

use Illuminate\Support\Str;
use NextDeveloper\IAM\Authorization\Roles\IAuthorizationRole;

/**
 * PlusClouds staff sales manager.
 *
 * Has every sales-manager privilege (row scoping, allowed operations and update/delete policies are inherited
 * from SalesManagerRole). On top of that it may add credit and gift balance to the accounting accounts of the
 * customers it manages (SalePersonAccountService::recharge() / giftBalance()).
 *
 * Deliberately NOT a customer-assignable role (RolesService::CUSTOMER_ASSIGNABLE_ROLES) and not a default or owner
 * role: only the platform hands it out. Assigning it also assigns sales-manager (RolesService::COMPANION_ROLES),
 * because many checks look for the sales-manager role by name.
 */
class PlusCloudsSalesManagerRole extends SalesManagerRole implements IAuthorizationRole
{
    public const NAME = 'plusclouds-sales-manager';

    public const LEVEL = 120;

    public const DESCRIPTION = 'PlusClouds Sales Manager: sales manager who can also add credit and gift balance to the accounts they manage.';

    public const DB_PREFIX = 'crm';

    // The parent reads its own constants through self::, so the identity getters are overridden here.
    public function getLevel(): int
    {
        return self::LEVEL;
    }

    public function getDescription(): string
    {
        return self::DESCRIPTION;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function canBeApplied($column)
    {
        return Str::startsWith($column, self::DB_PREFIX);
    }

    public function getDbPrefix()
    {
        return self::DB_PREFIX;
    }
}
