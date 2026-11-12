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

    public static function create(array $data)
    {
        return parent::create(self::prepareTokenForCreate($data));
    }

    public static function update($id, array $data)
    {
        return parent::update($id, self::prepareTokenForUpdate($data));
    }

    /**
     * A blank token is not a token: store nothing instead of an encrypted empty string.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function prepareTokenForCreate(array $data): array
    {
        unset($data['remove_token']);

        if (array_key_exists('token', $data) && blank($data['token'])) {
            unset($data['token']);
        }

        return $data;
    }

    /**
     * The token is write-only, so clients can't send it back. A missing or blank token keeps
     * the stored one; `remove_token: true` clears it.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function prepareTokenForUpdate(array $data): array
    {
        $removeToken = filter_var($data['remove_token'] ?? false, FILTER_VALIDATE_BOOLEAN);
        unset($data['remove_token']);

        if ($removeToken) {
            $data['token'] = null;

            return $data;
        }

        if (!array_key_exists('token', $data) || blank($data['token'])) {
            unset($data['token']);
        }

        return $data;
    }

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
