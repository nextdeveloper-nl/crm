<?php

namespace NextDeveloper\CRM\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use NextDeveloper\CRM\Database\Models\Campaigns;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCampaignsService;
use NextDeveloper\CRM\Services\CampaignsService;
use NextDeveloper\Flow\Database\Models\Pipelines;
use NextDeveloper\Flow\Database\Models\Stages;
use NextDeveloper\Flow\Services\PipelinesService;
use NextDeveloper\IAM\Database\Models\Accounts;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\IAM\Helpers\UserHelper;

class FixCampaignFlowLinksCommand extends Command
{
    protected $signature = 'crm:fix-campaign-flow-links
                            {--dry-run : Only report what would change, without writing}';

    protected $description = 'Backfills missing links between sales campaigns and flow pipelines: '
        . 'creates a sales campaign for orphan pipelines, and a pipeline for sales campaigns without one.';

    public function handle(): void
    {
        UserHelper::setAdminAsCurrentUser();

        $dryRun = (bool) $this->option('dry-run');

        $this->fixOrphanPipelines($dryRun);
        $this->fixCampaignsWithoutFlow($dryRun);

        $this->info('Done.');
    }

    /**
     * Flow pipelines (not templates, not system) that no crm_campaigns row points at.
     * Creates a sales campaign with the same name and attaches it to the pipeline both ways.
     */
    private function fixOrphanPipelines(bool $dryRun): void
    {
        $pipelines = Pipelines::withoutGlobalScope(AuthorizationScope::class)
            ->where('is_template', false)
            ->where('is_system', false)
            ->whereNull('deleted_at')
            ->get();

        $fixed = 0;

        foreach ($pipelines as $pipeline) {
            $hasCampaign = Campaigns::withoutGlobalScope(AuthorizationScope::class)
                ->where('flow_pipeline_id', $pipeline->id)
                ->exists();

            if ($hasCampaign) {
                continue;
            }

            $fixed++;
            $this->line("  Pipeline without campaign: [{$pipeline->uuid}] {$pipeline->name}");

            if ($dryRun) {
                continue;
            }

            $account = $pipeline->iam_account_id ? Accounts::find($pipeline->iam_account_id) : null;
            $user = $pipeline->iam_user_id ? Users::find($pipeline->iam_user_id) : null;

            $this->runAs($account, $user, function () use ($pipeline) {
                $firstStage = Stages::where('flow_pipeline_id', $pipeline->id)
                    ->orderBy('position')
                    ->first();

                // Bypasses CampaignsService::create() on purpose: that method auto-provisions
                // a *new* pipeline from campaign_type, which would conflict with attaching
                // this already-existing one.
                $campaign = AbstractCampaignsService::create([
                    'name'             => $pipeline->name,
                    'campaign_type'    => 'sales',
                    'status'           => 'draft',
                    'flow_pipeline_id' => $pipeline->id,
                    'flow_stage_id'    => $firstStage?->id,
                ]);

                PipelinesService::update($pipeline->uuid, [
                    'object_type' => str_replace('\\Database\\Models', '', Campaigns::class),
                    'object_id'   => $campaign->id,
                ]);

                Log::info(
                    __METHOD__ . '| Created campaign for orphan pipeline | pipeline: '
                    . $pipeline->id . ' campaign: ' . $campaign->id
                );
            });
        }

        $this->info("Orphan pipelines fixed: {$fixed}");
    }

    /**
     * Sales campaigns with no flow_pipeline_id. Creates a pipeline from the configured
     * 'sales' template and attaches it both ways.
     */
    private function fixCampaignsWithoutFlow(bool $dryRun): void
    {
        $templateId = config('crm.campaign_flow_templates.sales');

        $campaigns = Campaigns::withoutGlobalScope(AuthorizationScope::class)
            ->where('campaign_type', 'sales')
            ->whereNull('flow_pipeline_id')
            ->whereNull('deleted_at')
            ->get();

        $fixed = 0;

        foreach ($campaigns as $campaign) {
            $fixed++;
            $this->line("  Sales campaign without flow: [{$campaign->uuid}] {$campaign->name}");

            if ($dryRun) {
                continue;
            }

            $account = $campaign->iam_account_id ? Accounts::find($campaign->iam_account_id) : null;
            $user = $campaign->iam_user_id ? Users::find($campaign->iam_user_id) : null;

            $this->runAs($account, $user, function () use ($campaign, $templateId) {
                $pipeline = PipelinesService::createFromTemplate($templateId, $campaign->name);

                $firstStage = Stages::where('flow_pipeline_id', $pipeline->id)
                    ->orderBy('position')
                    ->first();

                CampaignsService::update($campaign->uuid, [
                    'flow_pipeline_id' => $pipeline->uuid,
                    'flow_stage_id'    => $firstStage?->uuid,
                ]);

                PipelinesService::update($pipeline->uuid, [
                    'object_type' => str_replace('\\Database\\Models', '', Campaigns::class),
                    'object_id'   => $campaign->id,
                ]);

                Log::info(
                    __METHOD__ . '| Created pipeline for campaign without flow | campaign: '
                    . $campaign->id . ' pipeline: ' . $pipeline->id
                );
            });
        }

        $this->info("Sales campaigns without flow fixed: {$fixed}");
    }

    /**
     * Runs the callback impersonating the record's original owner, so the created
     * campaign/pipeline gets that account's iam_account_id/iam_user_id instead of the
     * command's admin identity. Always restores the admin identity afterwards.
     */
    private function runAs(?Accounts $account, ?Users $user, callable $callback): void
    {
        if ($account && $user) {
            UserHelper::setCurrentUserAndAccount($user, $account);
        }

        try {
            $callback();
        } finally {
            UserHelper::setAdminAsCurrentUser();
        }
    }
}
