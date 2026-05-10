<?php

namespace NextDeveloper\CRM\Services;

use NextDeveloper\CRM\Services\AbstractServices\AbstractCampaignsService;
use NextDeveloper\Flow\Database\Models\Pipelines;
use NextDeveloper\Flow\Database\Models\Stages;

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
        $data = self::resolveFlowIds($data);

        return parent::create($data);
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