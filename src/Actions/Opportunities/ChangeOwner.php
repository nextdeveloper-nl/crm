<?php

namespace NextDeveloper\CRM\Actions\Opportunities;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\CRM\Database\Models\Opportunities;
use NextDeveloper\Events\Services\Events;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * Changes the owner (iam_user_id) of a CRM opportunity.
 * Only sales-managers and sales-admins are allowed to reassign ownership.
 */
class ChangeOwner extends AbstractAction
{
    public const EVENTS = [
        'owner-changed:NextDeveloper\CRM\Opportunities',
    ];

    public const PARAMS = [
        'iam_user_id' => 'string|required',
    ];

    /**
     * @throws NotAllowedException
     */
    public function __construct(Opportunities $opportunity, $params = null)
    {
        $this->model = $opportunity;

        parent::__construct($params);
    }

    /**
     * @throws NotAllowedException
     */
    public function handle(): void
    {
        $this->setProgress(0, 'Starting ownership change');

        if (
            !UserHelper::hasRole('sales-manager') &&
            !UserHelper::hasRole('sales-admin')
        ) {
            $this->setFinishedWithError('Only sales-managers and sales-admins can change the owner of an opportunity.');
            return;
        }

        $newOwner = UserHelper::getWithId($this->params['iam_user_id']);

        if (!$newOwner) {
            $this->setFinishedWithError('The specified user could not be found.');
            return;
        }

        $this->setProgress(50, 'Assigning new owner');

        // Run as admin because the current user may not have permission to write iam_user_id directly.
        UserHelper::runAsAdmin(function () use ($newOwner) {
            $this->model->update([
                'iam_user_id' => $newOwner->id,
            ]);
        });

        // Clear all cached representations of this opportunity so stale data is not served.
        CacheHelper::deleteKeys(Opportunities::class, $this->model->uuid);

        $this->setProgress(90, 'Firing event');
        Events::fire('owner-changed', $this->model->fresh());

        $this->setFinished('Opportunity owner changed to ' . $newOwner->fullname);
    }
}
