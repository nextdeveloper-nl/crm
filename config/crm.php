<?php

return [
    'scopes'    =>  [
        'global' => [
            //  Dont do this here because it makes infinite loop with user object.
            '\NextDeveloper\IAM\Database\Scopes\AuthorizationScope',
            '\NextDeveloper\Commons\Database\GlobalScopes\LimitScope',
            //  Tenant boundary, independent of the role AuthorizationScope picks. Inert while
            //  crm.tenant_isolation.enabled is false.
            '\NextDeveloper\CRM\Database\Scopes\TenantScope',
        ]
    ],

    //  Multi-tenancy. See docs/features/multi-tenancy-plan.md and schemas/changes/2026_11_14_crm_accounts_owner.sql.
    'tenant_isolation' => [
        //  On by default. Requires the owner column DDL (schemas/changes/2026_11_14_crm_accounts_owner.sql) on
        //  the database: the scope queries owner_iam_account_id. Set CRM_TENANT_ISOLATION_ENABLED=false to
        //  switch it off (e.g. on a database where the DDL is not applied yet).
        'enabled'             => env('CRM_TENANT_ISOLATION_ENABLED', true),
        //  The operator account (sees all tenants; owner of trigger-created crm_accounts). Falls back to
        //  leo.current_account_id when null.
        'platform_account_id' => env('CRM_PLATFORM_ACCOUNT_ID', null),
        //  Tables/views that carry owner_iam_account_id (the tenant that owns the CRM record).
        'owner_column_tables' => [
            'crm_accounts',
            'crm_accounts_perspective',
        ],
        //  table => column holding the OWNING tenant's iam_account_id (seller / creator account). Rows of other
        //  tenants are hidden whatever role applies; the platform account is unrestricted.
        'account_column_tables' => [
            'crm_opportunities'                    => 'iam_account_id',
            'crm_opportunities_perspective'        => 'iam_account_id',
            'crm_opportunities_performance'        => 'iam_account_id',
            'crm_quotes'                           => 'iam_account_id',
            'crm_quotes_perspective'               => 'iam_account_id',
            'crm_quote_items'                      => 'iam_account_id',
            'crm_quote_items_perspective'          => 'iam_account_id',
            'crm_calls'                            => 'iam_account_id',
            'crm_meetings'                         => 'iam_account_id',
            'crm_tasks'                            => 'iam_account_id',
            'crm_notes'                            => 'iam_account_id',
            'crm_projects'                         => 'iam_account_id',
            'crm_campaigns'                        => 'iam_account_id',
            'crm_campaign_targets_perspective'     => 'iam_account_id',
            //  iam_account_id of this view is the target user's own account; the owner is the campaign's account
            'crm_campaign_target_users_perspective' => 'responsible_account_id',
            'crm_targets'                          => 'iam_account_id',
            'crm_target_users_perspective'         => 'iam_account_id',
            'crm_email_templates'                  => 'iam_account_id',
            'crm_importers'                        => 'iam_account_id',
            'crm_importer_runs'                    => 'iam_account_id',
            //  iam_account_id = the managing (tenant) account
            'crm_account_managers'                 => 'iam_account_id',
            'crm_account_managers_performance'     => 'iam_account_id',
            'crm_user_managers'                    => 'iam_account_id',
            'crm_task_dependencies'                => 'iam_account_id',
            //  needs schemas/changes/2026_11_14_crm_account_managers_perspective_owner.sql (adds the column)
            'crm_account_managers_perspective'     => 'manager_iam_account_id',
        ],
        //  Global reference data every tenant may read (writes are governed by the roles, not by this scope).
        'shared_tables' => [
            'crm_industries',
            'crm_offerings',
            'crm_technologies',
            'crm_sector_focus',
            'crm_regulatory_compliance',
        ],
        //  Platform-wide aggregates / views: only the platform account may read them, everybody else gets no rows.
        'platform_only_tables' => [
            'crm_daily_new_accounts_performance',
            'crm_weekly_new_accounts_performance',
            'crm_monthly_new_accounts_performance',
            'crm_monthly_new_accounts_per_dist_performance',
            'crm_monthly_paying_customers_performance',
            'crm_leo_active_customers_perspective',
            'crm_stats_performances_perspective',
            //  no DDL in schemas/ yet, so the tenant column is unknown: closed until it is modelled
            'crm_account_managers_performances_perspective',
            'crm_account_users_perspective',
        ],
        //  Contact / user data. Views whose account column is the account a person belongs to (or the account of the
        //  messages): visible when that account is the tenant itself or one of the customers the tenant owns
        //  (crm_accounts.owner_iam_account_id = tenant).
        'owned_customer_account_tables' => [
            'crm_users_perspective'                  => 'iam_account_id',
            'crm_sales_people_perspective'           => 'iam_account_id',
            'crm_account_communications_perspective' => 'iam_account_id',
            'crm_user_emails_perspective'            => 'iam_account_id',
        ],
        //  crm_users has no account column: a contact is visible to a tenant when the person belongs to the tenant or
        //  one of its owned customers, is managed by the tenant (crm_user_managers) or is in one of its targets.
        'contact_tables' => [
            'crm_users',
        ],
        //  Link tables without any account column: owned through their parent row.
        //  table => [foreign key column, parent table, parent's owner column]
        'parent_scoped_tables' => [
            'crm_campaign_targets' => ['crm_campaign_id', 'crm_campaigns', 'iam_account_id'],
            'crm_target_users'     => ['crm_target_id', 'crm_targets', 'iam_account_id'],
        ],
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
