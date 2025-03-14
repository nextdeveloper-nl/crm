<?php

namespace NextDeveloper\CRM\Http\Requests\EmailTemplates;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class EmailTemplatesUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'subject' => 'nullable|string',
        'content' => 'nullable|string',
        'email_meta' => 'nullable|string',
        'crm_campaign_id' => 'nullable|exists:crm_campaigns,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}