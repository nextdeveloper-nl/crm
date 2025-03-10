<?php

namespace NextDeveloper\CRM\Http\Requests\Technologies;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class TechnologiesCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}