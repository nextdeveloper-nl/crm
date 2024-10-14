<?php

namespace NextDeveloper\CRM\Actions\Accounts;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\CRM\Database\Models\Accounts;
use NextDeveloper\CRM\Database\Models\Opportunities;
use NextDeveloper\Events\Services\Events;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;

/**
 * Class CreateOpportunity
 *
 * This action creates an opportunity for the related account.
 *
 * @package NextDeveloper\CRM\Actions\Accounts
 */
class CreateOpportunity extends AbstractAction
{
    /**
     * Events associated with this action.
     */
    public const EVENTS = [
        'created:NextDeveloper\CRM\Opportunities'
    ];

    /**
     * Initializes the action with the given CRM account.
     *
     * @param Accounts $accounts The account model instance.
     * @throws NotAllowedException
     */
    public function __construct(Accounts $accounts)
    {
        $this->model = $accounts;
        parent::__construct();
    }

    /**
     * Handles the process of creating an opportunity.
     *
     * This function performs the following steps:
     * 1. Sets initial progress.
     * 2. Checks if the account already has the 'Initial opportunity'.
     * 3. Creates the opportunity if it does not exist.
     * 4. Fires an event indicating the opportunity has been created.
     * 5. Set the process as finished.
     */
    public function handle(): void
    {
        // Set initial progress
        $this->setProgress(0, 'Starting to create opportunity');

        // Check if the account already has the 'Initial opportunity'
        $opportunity = Opportunities::withoutGlobalScope(AuthorizationScope::class)
            ->where('crm_account_id', $this->model->id)
            ->where('name', 'Initial opportunity')
            ->first();

        if (!$opportunity) {
            // Create the opportunity if it does not exist
            $opportunity = Opportunities::withoutGlobalScope(AuthorizationScope::class)
                ->create([
                    'crm_account_id' => $this->model->id,
                    'name'           => 'Initial opportunity',
                ]);

            $this->setProgress(80, 'Opportunity created');
        } else {
            $this->setProgress(50, 'Opportunity already exists');
        }

        // Fire event indicating the opportunity has been created
        $this->setProgress(90, 'Firing event');
        Events::fire('created', $opportunity);

        // Set the process as finished
        $this->setFinished();
    }
}
