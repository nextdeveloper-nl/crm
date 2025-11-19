<?php

namespace NextDeveloper\CRM\Actions\Quotes;


use App\Envelopes\CRM\Quotes\QuoteApprovedNotification;
use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Exceptions\NotAllowedException;
use NextDeveloper\Communication\Helpers\Communicate;
use NextDeveloper\CRM\Database\Models\Quotes;
use NextDeveloper\CRM\Database\Models\Users;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * This action sets the quote as approved
 */
class Approve extends AbstractAction
{
    /**
     * This action takes a user object and sets the quote as approved
     *
     * @param Quotes $quote
     * @param null $params
     * @param null $previousAction
     * @throws NotAllowedException
     */
    public function __construct(Quotes $quote, $params = null, $previousAction = null)
    {
        $this->model = $quote;

        parent::__construct($params, $previousAction);
    }

    public function handle(): void
    {
        $this->setProgress(0, 'Approving quote');

        if ($this->model->approval_level !== 'pending-approval') {
            $this->setFinished('Quote is not in a state that allows approval. Allowed state: pending-approval.');
            return;
        }

        if (!UserHelper::hasRole('accounting-manager')) {
            $this->setFinished('User is not authorized to approve quotes.');
            return;
        }

        $this->model->update([
            'approval_level' => 'approved',
        ]);

        // send notification to relevant parties about the approval
        $user = UserHelper::getUserWithId($this->model->iam_user_id, true);

        // You can implement notification logic here, e.g., sending emails or messages
        if ($user) {
            $envelope = new QuoteApprovedNotification($this->model, $user);
            (new Communicate($user))->sendEnvelope($envelope);
        }
        $this->setFinished('Quote approved');
    }
}
