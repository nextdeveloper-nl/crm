<?php

return [
    'scopes'    =>  [
        'global' => [
            //  Dont do this here because it makes infinite loop with user object.
            '\NextDeveloper\IAM\Database\Scopes\AuthorizationScope',
            '\NextDeveloper\Commons\Database\GlobalScopes\LimitScope',
        ]
    ],

    'linked_actions'    =>  [
        'created:NextDeveloper\CRM\QuoteItems' => \NextDeveloper\CRM\Actions\QuoteItems\ValidateQuoteItem::class
    ],

    //  Default flow.pipeline_templates id to use when a campaign is created with a matching
    //  campaign_type but no explicit flow_template_id was picked by the client.
    'campaign_flow_templates' => [
        'sales'     => 'b2b-sales',
        'marketing' => 'email-marketing',
    ],
];
