<?php

namespace NextDeveloper\CRM\Services;

use NextDeveloper\CRM\Database\Models\Campaigns;
use NextDeveloper\CRM\Services\AbstractServices\AbstractCampaignsService;
use NextDeveloper\Commons\Database\Models\Taggables;
use NextDeveloper\Commons\Database\Models\Tags;
use NextDeveloper\Flow\Database\Models\Pipelines;
use NextDeveloper\Flow\Database\Models\Stages;
use NextDeveloper\Flow\Services\PipelinesService;
use NextDeveloper\Commons\Exceptions\NotAllowedException;

/**
 * This class is responsible from managing the data for Campaigns
 *
 * Class CampaignsService.
 *
 * @package NextDeveloper\CRM\Database\Models
 */
class CampaignsService extends AbstractCampaignsService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    public static function create(array $data)
    {
        $data = self::provisionFlow($data);
        $data = self::resolveFlowIds($data);

        $campaign = parent::create($data);

        self::linkFlowPipelineToCampaign($campaign);

        return $campaign;
    }

    /**
     * If the client picked a flow_template_id (from flow.pipeline_templates), or requested a
     * campaign_type with no explicit template, create a live pipeline+stages for this campaign
     * from that config-defined template. If neither is given, the campaign is created without
     * a flow (opt-in behavior).
     */
    private static function provisionFlow(array $data): array
    {
        $templateId = $data['flow_template_id'] ?? null;
        $campaignType = $data['campaign_type'] ?? null;

        if (!$templateId && !$campaignType) {
            return $data;
        }

        if (!$templateId) {
            $templateId = config("crm.campaign_flow_templates.{$campaignType}");
        }

        if (!$templateId || !config("flow.pipeline_templates.{$templateId}")) {
            throw new NotAllowedException("Pipeline template '{$templateId}' not found.");
        }

        $pipeline = PipelinesService::createFromTemplate($templateId, $data['name'] ?? null);

        $firstStage = Stages::where('flow_pipeline_id', $pipeline->id)
            ->orderBy('position')
            ->first();

        $data['flow_pipeline_id'] = $pipeline->uuid;
        $data['flow_stage_id'] = $firstStage?->uuid;
        unset($data['flow_template_id']);

        return $data;
    }

    /**
     * The pipeline is provisioned before the campaign row exists, so its object_id can't be
     * set at creation time. Once the campaign has an id, point the pipeline's object_type /
     * object_id back at it so the reverse relation resolves.
     */
    private static function linkFlowPipelineToCampaign($campaign): void
    {
        if (!$campaign->flow_pipeline_id) {
            return;
        }

        $pipeline = Pipelines::find($campaign->flow_pipeline_id);

        if (!$pipeline) {
            return;
        }

        PipelinesService::update($pipeline->uuid, [
            'object_type' => str_replace('\\Database\\Models', '', get_class($campaign)),
            'object_id'   => $campaign->id,
        ]);
    }

    public static function update($id, array $data)
    {
        $data = self::resolveFlowIds($data);

        return parent::update($id, $data);
    }

    /**
     * Resolves a Campaign by its Facebook ad_id, so inbound Messenger/Lead Ads
     * events can attribute a conversation/lead back to the ad that produced it.
     *
     * Campaigns are matched by tag (`facebook_ad:<ad_id>`) rather than a dedicated
     * column, since crm_campaigns has no ad_id field and none is being added here.
     * Returns null when unmatched — attribution is enrichment, not a hard dependency.
     */
    public static function findByFacebookAdId(string $adId): ?Campaigns
    {
        $tag = Tags::withoutGlobalScopes()->where('name', 'facebook_ad:' . $adId)->first();

        if (!$tag) {
            return null;
        }

        $taggable = Taggables::withoutGlobalScopes()
            ->where('object_type', Campaigns::class)
            ->where('common_tags_id', $tag->id)
            ->first();

        return $taggable ? Campaigns::withoutGlobalScopes()->find($taggable->object_id) : null;
    }

    private static function resolveFlowIds(array $data): array
    {
        if (array_key_exists('flow_pipeline_id', $data)) {
            $pipeline = Pipelines::where('uuid', $data['flow_pipeline_id'])->first();
            $data['flow_pipeline_id'] = $pipeline?->id;
        }

        if (array_key_exists('flow_stage_id', $data)) {
            $stage = Stages::where('uuid', $data['flow_stage_id'])->first();
            $data['flow_stage_id'] = $stage?->id;
        }

        return $data;
    }

    /**
     * Soft-deletes the campaign's linked pipeline along with the campaign, so a deleted
     * campaign doesn't leave an orphaned live pipeline behind.
     */
    public static function delete($id)
    {
        $campaign = Campaigns::where('uuid', $id)->first();

        if ($campaign?->flow_pipeline_id) {
            $pipeline = Pipelines::find($campaign->flow_pipeline_id);

            if ($pipeline) {
                PipelinesService::delete($pipeline->uuid);
            }
        }

        return parent::delete($id);
    }

    /**
     * Restores a soft-deleted campaign and revives its linked pipeline along with it.
     *
     * @throws NotAllowedException if the campaign cannot be found.
     */
    public static function restore($id): Campaigns
    {
        $campaign = Campaigns::withTrashed()->where('uuid', $id)->first();

        if (!$campaign) {
            throw new NotAllowedException(
                'We cannot find the related object to restore. ' .
                'Maybe you dont have the permission to update this object?'
            );
        }

        $campaign->restore();

        if ($campaign->flow_pipeline_id) {
            $pipeline = Pipelines::withTrashed()->find($campaign->flow_pipeline_id);

            if ($pipeline) {
                PipelinesService::restore($pipeline->uuid);
            }
        }

        return $campaign->fresh();
    }
}