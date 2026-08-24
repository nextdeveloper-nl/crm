<?php

namespace NextDeveloper\CRM\Services;

use NextDeveloper\CRM\Services\AbstractServices\AbstractCampaignsService;
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

        return parent::create($data);
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

    public static function update($id, array $data)
    {
        $data = self::resolveFlowIds($data);

        return parent::update($id, $data);
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
}