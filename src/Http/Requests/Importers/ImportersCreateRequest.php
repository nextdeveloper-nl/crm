<?php

namespace NextDeveloper\CRM\Http\Requests\Importers;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class ImportersCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'source_type' => 'required|string',
            'status' => 'nullable|string',
            'config' => 'nullable|array',
            'credentials_enc' => 'nullable|string',
            'object_type' => 'nullable|string',
            'object_id' => 'nullable|integer',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}