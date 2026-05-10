<?php

namespace NextDeveloper\CRM\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use NextDeveloper\CRM\Database\Models\Campaigns;
use NextDeveloper\CRM\Jobs\Campaigns\GenerateSalesCampaignOpportunities;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\IAM\Helpers\UserHelper;

class GenerateSalesCampaignOpportunitiesCommand extends Command
{
    protected $signature = 'crm:generate-sales-campaign-opportunities
                            {--campaign= : UUID of a specific campaign to process (optional)}';

    protected $description = 'Dispatches opportunity generation jobs for all active sales campaigns.';

    public function handle(): void
    {
        UserHelper::setAdminAsCurrentUser();

        $query = Campaigns::withoutGlobalScope(AuthorizationScope::class)
            ->where('campaign_type', 'sales')
            ->where('status', 'active');

        if ($campaignUuid = $this->option('campaign')) {
            $query->where('uuid', $campaignUuid);
        }

        $campaigns = $query->get();

        if ($campaigns->isEmpty()) {
            $this->info('No active sales campaigns found.');
            return;
        }

        $this->info('Found ' . $campaigns->count() . ' active sales campaign(s). Dispatching jobs...');

        foreach ($campaigns as $campaign) {
            GenerateSalesCampaignOpportunities::dispatch($campaign);

            $this->line('  Dispatched: [' . $campaign->uuid . '] ' . $campaign->name);
            Log::info(__METHOD__ . '| Dispatched GenerateSalesCampaignOpportunities | campaign: ' . $campaign->id);
        }

        $this->info('Done.');
    }
}
