<?php

namespace NextDeveloper\CRM\Services;

use NextDeveloper\CRM\Services\AbstractServices\AbstractEmailTemplatesService;

/**
 * This class is responsible from managing the data for EmailTemplates
 *
 * Class EmailTemplatesService.
 */
class EmailTemplatesService extends AbstractEmailTemplatesService
{
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    /**
     * A template may be created with a subject only. The request middleware turns an empty body into null and
     * crm_email_templates.content does not accept null, so a missing body is stored as an empty string.
     *
     * @param  array<string, mixed>  $data
     */
    public static function create(array $data)
    {
        $data['content'] = $data['content'] ?? '';

        return parent::create($data);
    }

    /**
     * Clearing the body of a template arrives as null for the same reason, and is stored as an empty string.
     *
     * @param  string  $id
     * @param  array<string, mixed>  $data
     */
    public static function update($id, array $data)
    {
        if (array_key_exists('content', $data) && $data['content'] === null) {
            $data['content'] = '';
        }

        return parent::update($id, $data);
    }
}
