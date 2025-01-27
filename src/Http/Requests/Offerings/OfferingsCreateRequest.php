<?php

namespace NextDeveloper\CRM\Http\Requests\Offerings;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class OfferingsCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}