<?php

namespace NextDeveloper\CRM\Actions\Quotes;

use App\Jobs\CRM\Quotes\SendQuoteApprovalRequestNotice;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\CRM\Database\Models\Quotes;
use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * This action requests approval for a given quote.
 *
 * The action performs the following steps:
 * 1. Validates the quote's current approval state
 * 2. Verifies user authentication and authorization
 * 3. Updates the quote's approval level to pending
 * 4. Dispatches notification to approvers
 *
 * @package NextDeveloper\CRM\Actions\Quotes
 */
class RequestApproveQuote extends AbstractAction
{

    /**
     * The new approval level after requesting approval
     */
    private const PENDING_APPROVAL_LEVEL = 'pending-approval';

    /**
     * @throws NotAllowedException
     */
    public function __construct(Quotes $quote, $params = null, $previousAction = null)
    {
        $this->model = $quote;
        parent::__construct($params, $previousAction);
    }

    /**
     * Execute the action to request quote approval
     *
     * @return void
     * @throws \RuntimeException
     * @throws \Throwable
     */
    public function handle(): void
    {
        $this->setProgress(0, 'Initiating quote approval request');

        try {
            // Validate quote state
            if ($this->model->approval_level !== 'draft') {
                $message ='Quote is not in a state that allows approval requests. Allowed states: draft.';
                $this->setFinished($message);
                return;
            }
            $this->setProgress(20, 'Quote state validated');

            // Validate user authentication and authorization
            $user = UserHelper::me();

            if (!$user) {
                $message = 'You must be logged in to request quote approvals.';
                $this->setFinished($message);
                return;
            }
            $this->setProgress(40, 'User authentication validated');

            $accountUser = UserHelper::currentAccount();

            // Check if the user is the quote owner OR belongs to the same account
            $isOwner = $user->id == $this->model->iam_user_id;
            $isSameAccount = $accountUser && $this->model->iam_account_id == $accountUser->id;

            if (!$isOwner && !$isSameAccount) {
                $message = 'Only the owner of the quote or users from the same account can request quote approvals.';
                $this->setFinished($message);
                return;
            }
            $this->setProgress(60, 'User authorization validated');


            $this->model->update([
                'approval_level' => self::PENDING_APPROVAL_LEVEL,
            ]);

            // Refresh the model to ensure we have the latest data
            $this->model->refresh();

            $this->setProgress(80, 'Quote approval level updated');

            // Dispatch notification job
            SendQuoteApprovalRequestNotice::dispatch($this->model);

            $this->setFinished('Quote approval requested successfully');

        } catch (\RuntimeException | \Throwable $e) {
            $this->setFinishedWithError($e);
        }
    }
}
