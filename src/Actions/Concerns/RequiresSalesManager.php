<?php

namespace NextDeveloper\CRM\Actions\Concerns;

use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * Account administration actions (suspend, unsuspend, enable/disable account or service) change the state of a
 * customer's account, so only a sales manager (or a sales admin, as for Opportunities\ChangeOwner) may run them.
 * Before this the panel was the only thing hiding these actions from a sales person; the API accepted them.
 *
 * Call requireSalesManager() at the top of the action constructor, before parent::__construct(), so a refused
 * request does not leave an action record behind.
 */
trait RequiresSalesManager
{
    /**
     * @throws NotAllowedException when the current user is neither sales-manager nor sales-admin.
     */
    protected function requireSalesManager(): void
    {
        if (!UserHelper::hasRole('sales-manager') && !UserHelper::hasRole('sales-admin')) {
            throw new NotAllowedException('Only a sales manager can run this action.');
        }
    }

    /**
     * Suspending and unsuspending an account is a sales-admin decision: sales managers and sales persons can not
     * run it (they can hide or deactivate the customer for themselves instead).
     *
     * @throws NotAllowedException when the current user is not sales-admin.
     */
    protected function requireSalesAdmin(): void
    {
        if (!UserHelper::hasRole('sales-admin')) {
            throw new NotAllowedException('Only a sales admin can suspend or unsuspend accounts.');
        }
    }
}
