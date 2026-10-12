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
    //  Code-defined ("static") lead importers. Each entry is matched to its source by the source's own key
    //  (for facebook: the Page id of a connected Page channel) and its crm_importers row is created on first
    //  use, so no importer has to be set up by hand. config is the same shape as crm_importers.config, plus
    //  flow_stage_name (stage picked by name) and opportunity_name_template ({name} = the lead's name).
    'static_importers' => [
        [
            'key'         => 'facebook-monitoring-usa',
            'name'        => 'Facebook: Monitoring Form USA',
            'source_type' => 'facebook',
            'page_id'     => '1126077610598972',
            'config'      => [
                'flow_pipeline_id'          => '73df5d6b-2f6b-4377-bf19-23e7f885c925',
                'flow_stage_name'           => 'New lead',
                'opportunity_type'          => 'sales',
                'opportunity_name_template' => 'FB {name}',
                'default_country_code'      => '1',
                'dedupe_window_hours'       => 24,
                'forms'                     => [
                    '1759681921751453' => [],
                ],
            ],
        ],
    ],

    'campaign_flow_templates' => [
        'sales'     => 'b2b-sales',
        'marketing' => 'email-marketing',
    ],
];
