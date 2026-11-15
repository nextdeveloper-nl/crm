<?php

namespace NextDeveloper\CRM\Database\Models\Concerns;

use Illuminate\Auth\Access\AuthorizationException;
use NextDeveloper\CRM\Helpers\TenantHelper;

/**
 * Multi-tenancy: industries, offerings, technologies, sector focus and regulatory compliance are ONE global
 * catalog every tenant reads (TenantScope lists them as shared_tables). The roles grant create/update/delete on
 * them to every SalesAdmin, so a tenant admin could rename or delete entries that every other tenant uses.
 *
 * This guard sits on the model events, so it covers every write path (services, updateRaw, actions, observers).
 * Only the platform account, trusted server-side callers (UserHelper::bypassRolesCheck) and deployments with
 * isolation switched off may write.
 */
trait GuardsSharedCatalog
{
    public static function bootGuardsSharedCatalog(): void
    {
        foreach (['creating', 'updating', 'deleting', 'restoring'] as $event) {
            // 'restoring' only exists on SoftDeletes models
            if (!method_exists(static::class, $event)) {
                continue;
            }

            static::$event(function () {
                if (!TenantHelper::maySharedCatalogBeWritten()) {
                    throw new AuthorizationException('The shared CRM catalog can only be changed by the platform account.');
                }
            });
        }
    }
}
