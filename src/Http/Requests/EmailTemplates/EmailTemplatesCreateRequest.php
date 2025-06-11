<?php

namespace NextDeveloper\CRM\Http\Requests\EmailTemplates;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class EmailTemplatesCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'subject' => 'required|string',
            'content' => 'required|string',
            'email_meta' => 'nullable|string',
            'crm_campaign_id' => 'nullable|exists:crm_campaigns,uuid|uuid',
            'communication_channel_id' => 'nullable|exists:communication_channels,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}
