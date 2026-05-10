<?php

namespace NextDeveloper\CRM\Jobs\Campaigns;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use NextDeveloper\CRM\Database\Models\Accounts as CrmAccounts;
use NextDeveloper\CRM\Database\Models\Campaigns;
use NextDeveloper\CRM\Database\Models\CampaignTargetUsersPerspective;
use NextDeveloper\CRM\Database\Models\Opportunities;
use NextDeveloper\CRM\Services\OpportunitiesService;
use NextDeveloper\Flow\Database\Models\Pipelines;
use NextDeveloper\Flow\Database\Models\Stages;
use NextDeveloper\Flow\Services\ItemsService;
use NextDeveloper\IAM\Database\Models\Accounts as IamAccounts;
use NextDeveloper\IAM\Database\Models\Users as IamUsers;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\IAM\Helpers\UserHelper;

/**
 * Generates CRM opportunities for each unique target account in a sales campaign.
 * Only runs when the campaign type is 'sales' and status is 'Active'.
 */
class GenerateSalesCampaignOpportunities implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Campaigns $campaign;

    public function __construct(Campaigns $campaign)
    {
        $this->campaign = $campaign;
    }

    public function handle(): void
    {
        if ($this->campaign->campaign_type !== 'sales' || $this->campaign->status !== 'active') {
            Log::info(__METHOD__ . '| Skipping campaign ' . $this->campaign->id . ': type=' . $this->campaign->campaign_type . ', status=' . $this->campaign->status);
            return;
        }

        UserHelper::setAdminAsCurrentUser();

        $targetAccounts = CampaignTargetUsersPerspective::withoutGlobalScope(AuthorizationScope::class)
            ->where('crm_campaign_id', $this->campaign->id)
            ->get()
            ->unique('iam_account_id');

        foreach ($targetAccounts as $target) {
            $this->createOpportunityForAccount($target->iam_account_id);
        }
    }

    private function createFlowItem(Opportunities $opportunity, IamAccounts $iamAccount): void
    {
        Log::info(__METHOD__ . '| Starting flow item creation | opportunity: ' . $opportunity->id
            . ' | campaign_flow_pipeline_id: ' . $this->campaign->flow_pipeline_id
            . ' | campaign_flow_stage_id: ' . $this->campaign->flow_stage_id);

        $pipeline = Pipelines::withoutGlobalScopes()
            ->where('id', $this->campaign->flow_pipeline_id)
            ->first();

        Log::info(__METHOD__ . '| Pipeline lookup result: ' . ($pipeline ? 'found uuid=' . $pipeline->uuid : 'NOT FOUND'));

        $stage = Stages::withoutGlobalScopes()
            ->where('id', $this->campaign->flow_stage_id)
            ->first();

        Log::info(__METHOD__ . '| Stage lookup result: ' . ($stage ? 'found uuid=' . $stage->uuid : 'NOT FOUND'));

        if (!$pipeline || !$stage) {
            Log::warning(__METHOD__ . '| Aborting: pipeline or stage not found | campaign: ' . $this->campaign->id);
            return;
        }

        $iamUser = $opportunity->iam_user_id
            ? IamUsers::withoutGlobalScopes()->where('id', $opportunity->iam_user_id)->first()
            : null;

        Log::info(__METHOD__ . '| IAM user lookup result: ' . ($iamUser ? 'found uuid=' . $iamUser->uuid : 'not set'));

        $payload = [
            'flow_pipeline_id' => $pipeline->uuid,
            'flow_stage_id'    => $stage->uuid,
            'object_type'      => Opportunities::class,
            'object_id'        => $opportunity->id,
            'object_name'      => $opportunity->name,
            'iam_account_id'   => $iamAccount->uuid,
            'iam_user_id'      => $iamUser?->uuid,
        ];

        Log::info(__METHOD__ . '| Calling ItemsService::create with payload: ' . json_encode($payload));

        try {
            $item = ItemsService::create($payload);

            Log::info(__METHOD__ . '| Flow item created successfully | item: ' . $item->id
                . ' | opportunity: ' . $opportunity->id
                . ' | pipeline: ' . $pipeline->id
                . ' | stage: ' . $stage->id);
        } catch (\Exception $e) {
            Log::error(__METHOD__ . '| Failed to create flow item | opportunity: ' . $opportunity->id
                . ' | error: ' . $e->getMessage()
                . ' | trace: ' . $e->getTraceAsString());
        }
    }

    private function createOpportunityForAccount(int $iamAccountId): void
    {
        $alreadyExists = Opportunities::withoutGlobalScope(AuthorizationScope::class)
            ->where('crm_campaign_id', $this->campaign->id)
            ->where('iam_account_id', $iamAccountId)
            ->exists();

        if ($alreadyExists) {
            return;
        }

        $iamAccount = IamAccounts::withoutGlobalScope(AuthorizationScope::class)
            ->where('id', $iamAccountId)
            ->first();

        if (!$iamAccount) {
            Log::warning(__METHOD__ . '| IAM account not found: ' . $iamAccountId);
            return;
        }

        $crmAccount = CrmAccounts::withoutGlobalScope(AuthorizationScope::class)
            ->where('iam_account_id', $iamAccountId)
            ->first();

        $data = [
            'name'            => $this->campaign->name,
            'description'     => $this->campaign->description,
            'iam_account_id'  => $iamAccount->uuid,
            'crm_campaign_id' => $this->campaign->id,
            'type'            => 'sales',
        ];

        if ($crmAccount) {
            $data['crm_account_id'] = $crmAccount->uuid;
        }

        try {
            $opportunity = OpportunitiesService::create($data);

            if ($opportunity && $this->campaign->flow_pipeline_id && $this->campaign->flow_stage_id) {
                $this->createFlowItem($opportunity, $iamAccount);
            }

            Log::info(__METHOD__ . '| Opportunity created | campaign: ' . $this->campaign->id . ' | iam_account: ' . $iamAccountId);
        } catch (\Exception $e) {
            Log::error(__METHOD__ . '| Failed to create opportunity | campaign: ' . $this->campaign->id . ' | iam_account: ' . $iamAccountId . ' | ' . $e->getMessage());
        }
    }
}
