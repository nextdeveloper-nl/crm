<?php

namespace NextDeveloper\CRM\Http\Requests\ImporterRuns;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class ImporterRunsCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'crm_importer_id' => 'required|exists:crm_importers,uuid|uuid',
            'status' => 'nullable|string',
            'payload' => 'nullable|array',
            'payload_hash' => 'required|string',
            'error' => 'nullable|string',
            'object_type' => 'nullable|string',
            'object_id' => 'nullable|integer',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}