<?php

namespace NextDeveloper\CRM\Services;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use NextDeveloper\CRM\Database\Models\Projects;
use NextDeveloper\CRM\Services\AbstractServices\AbstractProjectsService;
use NextDeveloper\CRM\Services\Clients\ProjectClientService;

/**
 * This class is responsible from managing the data for Projects
 *
 * Class ProjectsService.
 *
 * @package NextDeveloper\CRM\Database\Models
 */
class ProjectsService extends AbstractProjectsService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE


    /**
     * @throws GuzzleException
     */
    public static function getByRef($ref): ?Projects
    {
        $model = parent::getByRef($ref);

        if ($model) {
            try {
                $projectClientService = new ProjectClientService($model);
                $issues = $projectClientService->getProjectIssues();
                $readme = $projectClientService->getProjectReadme();

                $model->readme = $readme;
                $model->issues = $issues;
            } catch (\Exception $e) {

                Log::error("CRM::ProjectsService@getByRef: " . $e->getMessage());

                $model->issues = [];
            }
        }

        return $model;
    }

}