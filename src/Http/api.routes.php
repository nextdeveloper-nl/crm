<?php

Route::prefix('crm')->group(
    function () {
        Route::prefix('regulatory-compliance')->group(
            function () {
                Route::get('/', 'RegulatoryCompliance\RegulatoryComplianceController@index');
                Route::get('/actions', 'RegulatoryCompliance\RegulatoryComplianceController@getActions');

                Route::get('{crm_regulatory_compliance}/tags ', 'RegulatoryCompliance\RegulatoryComplianceController@tags');
                Route::post('{crm_regulatory_compliance}/tags ', 'RegulatoryCompliance\RegulatoryComplianceController@saveTags');
                Route::get('{crm_regulatory_compliance}/addresses ', 'RegulatoryCompliance\RegulatoryComplianceController@addresses');
                Route::post('{crm_regulatory_compliance}/addresses ', 'RegulatoryCompliance\RegulatoryComplianceController@saveAddresses');

                Route::get('/{crm_regulatory_compliance}/{subObjects}', 'RegulatoryCompliance\RegulatoryComplianceController@relatedObjects');
                Route::get('/{crm_regulatory_compliance}', 'RegulatoryCompliance\RegulatoryComplianceController@show');

                Route::post('/', 'RegulatoryCompliance\RegulatoryComplianceController@store');
                Route::post('/{crm_regulatory_compliance}/do/{action}', 'RegulatoryCompliance\RegulatoryComplianceController@doAction');

                Route::patch('/{crm_regulatory_compliance}', 'RegulatoryCompliance\RegulatoryComplianceController@update');
                Route::delete('/{crm_regulatory_compliance}', 'RegulatoryCompliance\RegulatoryComplianceController@destroy');
            }
        );

        Route::prefix('account-managers')->group(
            function () {
                Route::get('/', 'AccountManagers\AccountManagersController@index');
                Route::get('/actions', 'AccountManagers\AccountManagersController@getActions');

                Route::get('{crm_account_managers}/tags ', 'AccountManagers\AccountManagersController@tags');
                Route::post('{crm_account_managers}/tags ', 'AccountManagers\AccountManagersController@saveTags');
                Route::get('{crm_account_managers}/addresses ', 'AccountManagers\AccountManagersController@addresses');
                Route::post('{crm_account_managers}/addresses ', 'AccountManagers\AccountManagersController@saveAddresses');

                Route::get('/{crm_account_managers}/{subObjects}', 'AccountManagers\AccountManagersController@relatedObjects');
                Route::get('/{crm_account_managers}', 'AccountManagers\AccountManagersController@show');

                Route::post('/', 'AccountManagers\AccountManagersController@store');
                Route::post('/{crm_account_managers}/do/{action}', 'AccountManagers\AccountManagersController@doAction');

                Route::patch('/{crm_account_managers}', 'AccountManagers\AccountManagersController@update');
                Route::delete('/{crm_account_managers}', 'AccountManagers\AccountManagersController@destroy');
            }
        );

        Route::prefix('calls')->group(
            function () {
                Route::get('/', 'Calls\CallsController@index');
                Route::get('/actions', 'Calls\CallsController@getActions');

                Route::get('{crm_calls}/tags ', 'Calls\CallsController@tags');
                Route::post('{crm_calls}/tags ', 'Calls\CallsController@saveTags');
                Route::get('{crm_calls}/addresses ', 'Calls\CallsController@addresses');
                Route::post('{crm_calls}/addresses ', 'Calls\CallsController@saveAddresses');

                Route::get('/{crm_calls}/{subObjects}', 'Calls\CallsController@relatedObjects');
                Route::get('/{crm_calls}', 'Calls\CallsController@show');

                Route::post('/', 'Calls\CallsController@store');
                Route::post('/{crm_calls}/do/{action}', 'Calls\CallsController@doAction');

                Route::patch('/{crm_calls}', 'Calls\CallsController@update');
                Route::delete('/{crm_calls}', 'Calls\CallsController@destroy');
            }
        );

        Route::prefix('accounts')->group(
            function () {
                Route::get('/', 'Accounts\AccountsController@index');
                Route::get('/actions', 'Accounts\AccountsController@getActions');

                Route::get('{crm_accounts}/tags ', 'Accounts\AccountsController@tags');
                Route::post('{crm_accounts}/tags ', 'Accounts\AccountsController@saveTags');
                Route::get('{crm_accounts}/addresses ', 'Accounts\AccountsController@addresses');
                Route::post('{crm_accounts}/addresses ', 'Accounts\AccountsController@saveAddresses');

                Route::get('/{crm_accounts}/{subObjects}', 'Accounts\AccountsController@relatedObjects');
                Route::get('/{crm_accounts}', 'Accounts\AccountsController@show');

                Route::post('/', 'Accounts\AccountsController@store');
                Route::post('/{crm_accounts}/do/{action}', 'Accounts\AccountsController@doAction');

                Route::patch('/{crm_accounts}', 'Accounts\AccountsController@update');
                Route::delete('/{crm_accounts}', 'Accounts\AccountsController@destroy');
            }
        );

        Route::prefix('email-templates')->group(
            function () {
                Route::get('/', 'EmailTemplates\EmailTemplatesController@index');
                Route::get('/actions', 'EmailTemplates\EmailTemplatesController@getActions');

                Route::get('{crm_email_templates}/tags ', 'EmailTemplates\EmailTemplatesController@tags');
                Route::post('{crm_email_templates}/tags ', 'EmailTemplates\EmailTemplatesController@saveTags');
                Route::get('{crm_email_templates}/addresses ', 'EmailTemplates\EmailTemplatesController@addresses');
                Route::post('{crm_email_templates}/addresses ', 'EmailTemplates\EmailTemplatesController@saveAddresses');

                Route::get('/{crm_email_templates}/{subObjects}', 'EmailTemplates\EmailTemplatesController@relatedObjects');
                Route::get('/{crm_email_templates}', 'EmailTemplates\EmailTemplatesController@show');

                Route::post('/', 'EmailTemplates\EmailTemplatesController@store');
                Route::post('/{crm_email_templates}/do/{action}', 'EmailTemplates\EmailTemplatesController@doAction');

                Route::patch('/{crm_email_templates}', 'EmailTemplates\EmailTemplatesController@update');
                Route::delete('/{crm_email_templates}', 'EmailTemplates\EmailTemplatesController@destroy');
            }
        );

        Route::prefix('meetings')->group(
            function () {
                Route::get('/', 'Meetings\MeetingsController@index');
                Route::get('/actions', 'Meetings\MeetingsController@getActions');

                Route::get('{crm_meetings}/tags ', 'Meetings\MeetingsController@tags');
                Route::post('{crm_meetings}/tags ', 'Meetings\MeetingsController@saveTags');
                Route::get('{crm_meetings}/addresses ', 'Meetings\MeetingsController@addresses');
                Route::post('{crm_meetings}/addresses ', 'Meetings\MeetingsController@saveAddresses');

                Route::get('/{crm_meetings}/{subObjects}', 'Meetings\MeetingsController@relatedObjects');
                Route::get('/{crm_meetings}', 'Meetings\MeetingsController@show');

                Route::post('/', 'Meetings\MeetingsController@store');
                Route::post('/{crm_meetings}/do/{action}', 'Meetings\MeetingsController@doAction');

                Route::patch('/{crm_meetings}', 'Meetings\MeetingsController@update');
                Route::delete('/{crm_meetings}', 'Meetings\MeetingsController@destroy');
            }
        );

        Route::prefix('offerings')->group(
            function () {
                Route::get('/', 'Offerings\OfferingsController@index');
                Route::get('/actions', 'Offerings\OfferingsController@getActions');

                Route::get('{crm_offerings}/tags ', 'Offerings\OfferingsController@tags');
                Route::post('{crm_offerings}/tags ', 'Offerings\OfferingsController@saveTags');
                Route::get('{crm_offerings}/addresses ', 'Offerings\OfferingsController@addresses');
                Route::post('{crm_offerings}/addresses ', 'Offerings\OfferingsController@saveAddresses');

                Route::get('/{crm_offerings}/{subObjects}', 'Offerings\OfferingsController@relatedObjects');
                Route::get('/{crm_offerings}', 'Offerings\OfferingsController@show');

                Route::post('/', 'Offerings\OfferingsController@store');
                Route::post('/{crm_offerings}/do/{action}', 'Offerings\OfferingsController@doAction');

                Route::patch('/{crm_offerings}', 'Offerings\OfferingsController@update');
                Route::delete('/{crm_offerings}', 'Offerings\OfferingsController@destroy');
            }
        );

        Route::prefix('projects')->group(
            function () {
                Route::get('/', 'Projects\ProjectsController@index');
                Route::get('/actions', 'Projects\ProjectsController@getActions');

                Route::get('{crm_projects}/tags ', 'Projects\ProjectsController@tags');
                Route::post('{crm_projects}/tags ', 'Projects\ProjectsController@saveTags');
                Route::get('{crm_projects}/addresses ', 'Projects\ProjectsController@addresses');
                Route::post('{crm_projects}/addresses ', 'Projects\ProjectsController@saveAddresses');

                Route::get('/{crm_projects}/{subObjects}', 'Projects\ProjectsController@relatedObjects');
                Route::get('/{crm_projects}', 'Projects\ProjectsController@show');

                Route::post('/', 'Projects\ProjectsController@store');
                Route::post('/{crm_projects}/do/{action}', 'Projects\ProjectsController@doAction');

                Route::patch('/{crm_projects}', 'Projects\ProjectsController@update');
                Route::delete('/{crm_projects}', 'Projects\ProjectsController@destroy');
            }
        );

        Route::prefix('notes')->group(
            function () {
                Route::get('/', 'Notes\NotesController@index');
                Route::get('/actions', 'Notes\NotesController@getActions');

                Route::get('{crm_notes}/tags ', 'Notes\NotesController@tags');
                Route::post('{crm_notes}/tags ', 'Notes\NotesController@saveTags');
                Route::get('{crm_notes}/addresses ', 'Notes\NotesController@addresses');
                Route::post('{crm_notes}/addresses ', 'Notes\NotesController@saveAddresses');

                Route::get('/{crm_notes}/{subObjects}', 'Notes\NotesController@relatedObjects');
                Route::get('/{crm_notes}', 'Notes\NotesController@show');

                Route::post('/', 'Notes\NotesController@store');
                Route::post('/{crm_notes}/do/{action}', 'Notes\NotesController@doAction');

                Route::patch('/{crm_notes}', 'Notes\NotesController@update');
                Route::delete('/{crm_notes}', 'Notes\NotesController@destroy');
            }
        );

        Route::prefix('quotes')->group(
            function () {
                Route::get('/', 'Quotes\QuotesController@index');
                Route::get('/actions', 'Quotes\QuotesController@getActions');

                Route::get('{crm_quotes}/tags ', 'Quotes\QuotesController@tags');
                Route::post('{crm_quotes}/tags ', 'Quotes\QuotesController@saveTags');
                Route::get('{crm_quotes}/addresses ', 'Quotes\QuotesController@addresses');
                Route::post('{crm_quotes}/addresses ', 'Quotes\QuotesController@saveAddresses');

                Route::get('/{crm_quotes}/{subObjects}', 'Quotes\QuotesController@relatedObjects');
                Route::get('/{crm_quotes}', 'Quotes\QuotesController@show');

                Route::post('/', 'Quotes\QuotesController@store');
                Route::post('/{crm_quotes}/do/{action}', 'Quotes\QuotesController@doAction');

                Route::patch('/{crm_quotes}', 'Quotes\QuotesController@update');
                Route::delete('/{crm_quotes}', 'Quotes\QuotesController@destroy');
            }
        );

        Route::prefix('industries')->group(
            function () {
                Route::get('/', 'Industries\IndustriesController@index');
                Route::get('/actions', 'Industries\IndustriesController@getActions');

                Route::get('{crm_industries}/tags ', 'Industries\IndustriesController@tags');
                Route::post('{crm_industries}/tags ', 'Industries\IndustriesController@saveTags');
                Route::get('{crm_industries}/addresses ', 'Industries\IndustriesController@addresses');
                Route::post('{crm_industries}/addresses ', 'Industries\IndustriesController@saveAddresses');

                Route::get('/{crm_industries}/{subObjects}', 'Industries\IndustriesController@relatedObjects');
                Route::get('/{crm_industries}', 'Industries\IndustriesController@show');

                Route::post('/', 'Industries\IndustriesController@store');
                Route::post('/{crm_industries}/do/{action}', 'Industries\IndustriesController@doAction');

                Route::patch('/{crm_industries}', 'Industries\IndustriesController@update');
                Route::delete('/{crm_industries}', 'Industries\IndustriesController@destroy');
            }
        );

        Route::prefix('opportunities')->group(
            function () {
                Route::get('/', 'Opportunities\OpportunitiesController@index');
                Route::get('/actions', 'Opportunities\OpportunitiesController@getActions');

                Route::get('{crm_opportunities}/tags ', 'Opportunities\OpportunitiesController@tags');
                Route::post('{crm_opportunities}/tags ', 'Opportunities\OpportunitiesController@saveTags');
                Route::get('{crm_opportunities}/addresses ', 'Opportunities\OpportunitiesController@addresses');
                Route::post('{crm_opportunities}/addresses ', 'Opportunities\OpportunitiesController@saveAddresses');

                Route::get('/{crm_opportunities}/{subObjects}', 'Opportunities\OpportunitiesController@relatedObjects');
                Route::get('/{crm_opportunities}', 'Opportunities\OpportunitiesController@show');

                Route::post('/', 'Opportunities\OpportunitiesController@store');
                Route::post('/{crm_opportunities}/do/{action}', 'Opportunities\OpportunitiesController@doAction');

                Route::patch('/{crm_opportunities}', 'Opportunities\OpportunitiesController@update');
                Route::delete('/{crm_opportunities}', 'Opportunities\OpportunitiesController@destroy');
            }
        );

        Route::prefix('tasks')->group(
            function () {
                Route::get('/', 'Tasks\TasksController@index');
                Route::get('/actions', 'Tasks\TasksController@getActions');

                Route::get('{crm_tasks}/tags ', 'Tasks\TasksController@tags');
                Route::post('{crm_tasks}/tags ', 'Tasks\TasksController@saveTags');
                Route::get('{crm_tasks}/addresses ', 'Tasks\TasksController@addresses');
                Route::post('{crm_tasks}/addresses ', 'Tasks\TasksController@saveAddresses');

                Route::get('/{crm_tasks}/{subObjects}', 'Tasks\TasksController@relatedObjects');
                Route::get('/{crm_tasks}', 'Tasks\TasksController@show');

                Route::post('/', 'Tasks\TasksController@store');
                Route::post('/{crm_tasks}/do/{action}', 'Tasks\TasksController@doAction');

                Route::patch('/{crm_tasks}', 'Tasks\TasksController@update');
                Route::delete('/{crm_tasks}', 'Tasks\TasksController@destroy');
            }
        );

        Route::prefix('users')->group(
            function () {
                Route::get('/', 'Users\UsersController@index');
                Route::get('/actions', 'Users\UsersController@getActions');

                Route::get('{crm_users}/tags ', 'Users\UsersController@tags');
                Route::post('{crm_users}/tags ', 'Users\UsersController@saveTags');
                Route::get('{crm_users}/addresses ', 'Users\UsersController@addresses');
                Route::post('{crm_users}/addresses ', 'Users\UsersController@saveAddresses');

                Route::get('/{crm_users}/{subObjects}', 'Users\UsersController@relatedObjects');
                Route::get('/{crm_users}', 'Users\UsersController@show');

                Route::post('/', 'Users\UsersController@store');
                Route::post('/{crm_users}/do/{action}', 'Users\UsersController@doAction');

                Route::patch('/{crm_users}', 'Users\UsersController@update');
                Route::delete('/{crm_users}', 'Users\UsersController@destroy');
            }
        );

        Route::prefix('campaigns')->group(
            function () {
                Route::get('/', 'Campaigns\CampaignsController@index');
                Route::get('/actions', 'Campaigns\CampaignsController@getActions');

                Route::get('{crm_campaigns}/tags ', 'Campaigns\CampaignsController@tags');
                Route::post('{crm_campaigns}/tags ', 'Campaigns\CampaignsController@saveTags');
                Route::get('{crm_campaigns}/addresses ', 'Campaigns\CampaignsController@addresses');
                Route::post('{crm_campaigns}/addresses ', 'Campaigns\CampaignsController@saveAddresses');

                Route::get('/{crm_campaigns}/{subObjects}', 'Campaigns\CampaignsController@relatedObjects');
                Route::get('/{crm_campaigns}', 'Campaigns\CampaignsController@show');

                Route::post('/', 'Campaigns\CampaignsController@store');
                Route::post('/{crm_campaigns}/do/{action}', 'Campaigns\CampaignsController@doAction');

                Route::patch('/{crm_campaigns}', 'Campaigns\CampaignsController@update');
                Route::delete('/{crm_campaigns}', 'Campaigns\CampaignsController@destroy');
            }
        );

        Route::prefix('campaign-targets')->group(
            function () {
                Route::get('/', 'CampaignTargets\CampaignTargetsController@index');
                Route::get('/actions', 'CampaignTargets\CampaignTargetsController@getActions');

                Route::get('{crm_campaign_targets}/tags ', 'CampaignTargets\CampaignTargetsController@tags');
                Route::post('{crm_campaign_targets}/tags ', 'CampaignTargets\CampaignTargetsController@saveTags');
                Route::get('{crm_campaign_targets}/addresses ', 'CampaignTargets\CampaignTargetsController@addresses');
                Route::post('{crm_campaign_targets}/addresses ', 'CampaignTargets\CampaignTargetsController@saveAddresses');

                Route::get('/{crm_campaign_targets}/{subObjects}', 'CampaignTargets\CampaignTargetsController@relatedObjects');
                Route::get('/{crm_campaign_targets}', 'CampaignTargets\CampaignTargetsController@show');

                Route::post('/', 'CampaignTargets\CampaignTargetsController@store');
                Route::post('/{crm_campaign_targets}/do/{action}', 'CampaignTargets\CampaignTargetsController@doAction');

                Route::patch('/{crm_campaign_targets}', 'CampaignTargets\CampaignTargetsController@update');
                Route::delete('/{crm_campaign_targets}', 'CampaignTargets\CampaignTargetsController@destroy');
            }
        );

        Route::prefix('target-users')->group(
            function () {
                Route::get('/', 'TargetUsers\TargetUsersController@index');
                Route::get('/actions', 'TargetUsers\TargetUsersController@getActions');

                Route::get('{crm_target_users}/tags ', 'TargetUsers\TargetUsersController@tags');
                Route::post('{crm_target_users}/tags ', 'TargetUsers\TargetUsersController@saveTags');
                Route::get('{crm_target_users}/addresses ', 'TargetUsers\TargetUsersController@addresses');
                Route::post('{crm_target_users}/addresses ', 'TargetUsers\TargetUsersController@saveAddresses');

                Route::get('/{crm_target_users}/{subObjects}', 'TargetUsers\TargetUsersController@relatedObjects');
                Route::get('/{crm_target_users}', 'TargetUsers\TargetUsersController@show');

                Route::post('/', 'TargetUsers\TargetUsersController@store');
                Route::post('/{crm_target_users}/do/{action}', 'TargetUsers\TargetUsersController@doAction');

                Route::patch('/{crm_target_users}', 'TargetUsers\TargetUsersController@update');
                Route::delete('/{crm_target_users}', 'TargetUsers\TargetUsersController@destroy');
            }
        );

        Route::prefix('sector-focus')->group(
            function () {
                Route::get('/', 'SectorFocus\SectorFocusController@index');
                Route::get('/actions', 'SectorFocus\SectorFocusController@getActions');

                Route::get('{crm_sector_focus}/tags ', 'SectorFocus\SectorFocusController@tags');
                Route::post('{crm_sector_focus}/tags ', 'SectorFocus\SectorFocusController@saveTags');
                Route::get('{crm_sector_focus}/addresses ', 'SectorFocus\SectorFocusController@addresses');
                Route::post('{crm_sector_focus}/addresses ', 'SectorFocus\SectorFocusController@saveAddresses');

                Route::get('/{crm_sector_focus}/{subObjects}', 'SectorFocus\SectorFocusController@relatedObjects');
                Route::get('/{crm_sector_focus}', 'SectorFocus\SectorFocusController@show');

                Route::post('/', 'SectorFocus\SectorFocusController@store');
                Route::post('/{crm_sector_focus}/do/{action}', 'SectorFocus\SectorFocusController@doAction');

                Route::patch('/{crm_sector_focus}', 'SectorFocus\SectorFocusController@update');
                Route::delete('/{crm_sector_focus}', 'SectorFocus\SectorFocusController@destroy');
            }
        );

        Route::prefix('quote-items')->group(
            function () {
                Route::get('/', 'QuoteItems\QuoteItemsController@index');
                Route::get('/actions', 'QuoteItems\QuoteItemsController@getActions');

                Route::get('{crm_quote_items}/tags ', 'QuoteItems\QuoteItemsController@tags');
                Route::post('{crm_quote_items}/tags ', 'QuoteItems\QuoteItemsController@saveTags');
                Route::get('{crm_quote_items}/addresses ', 'QuoteItems\QuoteItemsController@addresses');
                Route::post('{crm_quote_items}/addresses ', 'QuoteItems\QuoteItemsController@saveAddresses');

                Route::get('/{crm_quote_items}/{subObjects}', 'QuoteItems\QuoteItemsController@relatedObjects');
                Route::get('/{crm_quote_items}', 'QuoteItems\QuoteItemsController@show');

                Route::post('/', 'QuoteItems\QuoteItemsController@store');
                Route::post('/{crm_quote_items}/do/{action}', 'QuoteItems\QuoteItemsController@doAction');

                Route::patch('/{crm_quote_items}', 'QuoteItems\QuoteItemsController@update');
                Route::delete('/{crm_quote_items}', 'QuoteItems\QuoteItemsController@destroy');
            }
        );

        Route::prefix('technologies')->group(
            function () {
                Route::get('/', 'Technologies\TechnologiesController@index');
                Route::get('/actions', 'Technologies\TechnologiesController@getActions');

                Route::get('{crm_technologies}/tags ', 'Technologies\TechnologiesController@tags');
                Route::post('{crm_technologies}/tags ', 'Technologies\TechnologiesController@saveTags');
                Route::get('{crm_technologies}/addresses ', 'Technologies\TechnologiesController@addresses');
                Route::post('{crm_technologies}/addresses ', 'Technologies\TechnologiesController@saveAddresses');

                Route::get('/{crm_technologies}/{subObjects}', 'Technologies\TechnologiesController@relatedObjects');
                Route::get('/{crm_technologies}', 'Technologies\TechnologiesController@show');

                Route::post('/', 'Technologies\TechnologiesController@store');
                Route::post('/{crm_technologies}/do/{action}', 'Technologies\TechnologiesController@doAction');

                Route::patch('/{crm_technologies}', 'Technologies\TechnologiesController@update');
                Route::delete('/{crm_technologies}', 'Technologies\TechnologiesController@destroy');
            }
        );

        Route::prefix('targets')->group(
            function () {
                Route::get('/', 'Targets\TargetsController@index');
                Route::get('/actions', 'Targets\TargetsController@getActions');

                Route::get('{crm_targets}/tags ', 'Targets\TargetsController@tags');
                Route::post('{crm_targets}/tags ', 'Targets\TargetsController@saveTags');
                Route::get('{crm_targets}/addresses ', 'Targets\TargetsController@addresses');
                Route::post('{crm_targets}/addresses ', 'Targets\TargetsController@saveAddresses');

                Route::get('/{crm_targets}/{subObjects}', 'Targets\TargetsController@relatedObjects');
                Route::get('/{crm_targets}', 'Targets\TargetsController@show');

                Route::post('/', 'Targets\TargetsController@store');
                Route::post('/{crm_targets}/do/{action}', 'Targets\TargetsController@doAction');

                Route::patch('/{crm_targets}', 'Targets\TargetsController@update');
                Route::delete('/{crm_targets}', 'Targets\TargetsController@destroy');
            }
        );

        Route::prefix('user-managers')->group(
            function () {
                Route::get('/', 'UserManagers\UserManagersController@index');
                Route::get('/actions', 'UserManagers\UserManagersController@getActions');

                Route::get('{crm_user_managers}/tags ', 'UserManagers\UserManagersController@tags');
                Route::post('{crm_user_managers}/tags ', 'UserManagers\UserManagersController@saveTags');
                Route::get('{crm_user_managers}/addresses ', 'UserManagers\UserManagersController@addresses');
                Route::post('{crm_user_managers}/addresses ', 'UserManagers\UserManagersController@saveAddresses');

                Route::get('/{crm_user_managers}/{subObjects}', 'UserManagers\UserManagersController@relatedObjects');
                Route::get('/{crm_user_managers}', 'UserManagers\UserManagersController@show');

                Route::post('/', 'UserManagers\UserManagersController@store');
                Route::post('/{crm_user_managers}/do/{action}', 'UserManagers\UserManagersController@doAction');

                Route::patch('/{crm_user_managers}', 'UserManagers\UserManagersController@update');
                Route::delete('/{crm_user_managers}', 'UserManagers\UserManagersController@destroy');
            }
        );

        Route::prefix('quotes-perspective')->group(
            function () {
                Route::get('/', 'QuotesPerspective\QuotesPerspectiveController@index');
                Route::get('/actions', 'QuotesPerspective\QuotesPerspectiveController@getActions');

                Route::get('{crm_quotes_perspective}/tags ', 'QuotesPerspective\QuotesPerspectiveController@tags');
                Route::post('{crm_quotes_perspective}/tags ', 'QuotesPerspective\QuotesPerspectiveController@saveTags');
                Route::get('{crm_quotes_perspective}/addresses ', 'QuotesPerspective\QuotesPerspectiveController@addresses');
                Route::post('{crm_quotes_perspective}/addresses ', 'QuotesPerspective\QuotesPerspectiveController@saveAddresses');

                Route::get('/{crm_quotes_perspective}/{subObjects}', 'QuotesPerspective\QuotesPerspectiveController@relatedObjects');
                Route::get('/{crm_quotes_perspective}', 'QuotesPerspective\QuotesPerspectiveController@show');

                Route::post('/', 'QuotesPerspective\QuotesPerspectiveController@store');
                Route::post('/{crm_quotes_perspective}/do/{action}', 'QuotesPerspective\QuotesPerspectiveController@doAction');

                Route::patch('/{crm_quotes_perspective}', 'QuotesPerspective\QuotesPerspectiveController@update');
                Route::delete('/{crm_quotes_perspective}', 'QuotesPerspective\QuotesPerspectiveController@destroy');
            }
        );

        Route::prefix('accounts-perspective')->group(
            function () {
                Route::get('/', 'AccountsPerspective\AccountsPerspectiveController@index');
                Route::get('/actions', 'AccountsPerspective\AccountsPerspectiveController@getActions');

                Route::get('{crm_accounts_perspective}/tags ', 'AccountsPerspective\AccountsPerspectiveController@tags');
                Route::post('{crm_accounts_perspective}/tags ', 'AccountsPerspective\AccountsPerspectiveController@saveTags');
                Route::get('{crm_accounts_perspective}/addresses ', 'AccountsPerspective\AccountsPerspectiveController@addresses');
                Route::post('{crm_accounts_perspective}/addresses ', 'AccountsPerspective\AccountsPerspectiveController@saveAddresses');

                Route::get('/{crm_accounts_perspective}/{subObjects}', 'AccountsPerspective\AccountsPerspectiveController@relatedObjects');
                Route::get('/{crm_accounts_perspective}', 'AccountsPerspective\AccountsPerspectiveController@show');

                Route::post('/', 'AccountsPerspective\AccountsPerspectiveController@store');
                Route::post('/{crm_accounts_perspective}/do/{action}', 'AccountsPerspective\AccountsPerspectiveController@doAction');

                Route::patch('/{crm_accounts_perspective}', 'AccountsPerspective\AccountsPerspectiveController@update');
                Route::delete('/{crm_accounts_perspective}', 'AccountsPerspective\AccountsPerspectiveController@destroy');
            }
        );

        Route::prefix('opportunities-perspective')->group(
            function () {
                Route::get('/', 'OpportunitiesPerspective\OpportunitiesPerspectiveController@index');
                Route::get('/actions', 'OpportunitiesPerspective\OpportunitiesPerspectiveController@getActions');

                Route::get('{crm_opportunities_perspective}/tags ', 'OpportunitiesPerspective\OpportunitiesPerspectiveController@tags');
                Route::post('{crm_opportunities_perspective}/tags ', 'OpportunitiesPerspective\OpportunitiesPerspectiveController@saveTags');
                Route::get('{crm_opportunities_perspective}/addresses ', 'OpportunitiesPerspective\OpportunitiesPerspectiveController@addresses');
                Route::post('{crm_opportunities_perspective}/addresses ', 'OpportunitiesPerspective\OpportunitiesPerspectiveController@saveAddresses');

                Route::get('/{crm_opportunities_perspective}/{subObjects}', 'OpportunitiesPerspective\OpportunitiesPerspectiveController@relatedObjects');
                Route::get('/{crm_opportunities_perspective}', 'OpportunitiesPerspective\OpportunitiesPerspectiveController@show');

                Route::post('/', 'OpportunitiesPerspective\OpportunitiesPerspectiveController@store');
                Route::post('/{crm_opportunities_perspective}/do/{action}', 'OpportunitiesPerspective\OpportunitiesPerspectiveController@doAction');

                Route::patch('/{crm_opportunities_perspective}', 'OpportunitiesPerspective\OpportunitiesPerspectiveController@update');
                Route::delete('/{crm_opportunities_perspective}', 'OpportunitiesPerspective\OpportunitiesPerspectiveController@destroy');
            }
        );

        Route::prefix('target-users-perspective')->group(
            function () {
                Route::get('/', 'TargetUsersPerspective\TargetUsersPerspectiveController@index');
                Route::get('/actions', 'TargetUsersPerspective\TargetUsersPerspectiveController@getActions');

                Route::get('{crm_target_users_perspective}/tags ', 'TargetUsersPerspective\TargetUsersPerspectiveController@tags');
                Route::post('{crm_target_users_perspective}/tags ', 'TargetUsersPerspective\TargetUsersPerspectiveController@saveTags');
                Route::get('{crm_target_users_perspective}/addresses ', 'TargetUsersPerspective\TargetUsersPerspectiveController@addresses');
                Route::post('{crm_target_users_perspective}/addresses ', 'TargetUsersPerspective\TargetUsersPerspectiveController@saveAddresses');

                Route::get('/{crm_target_users_perspective}/{subObjects}', 'TargetUsersPerspective\TargetUsersPerspectiveController@relatedObjects');
                Route::get('/{crm_target_users_perspective}', 'TargetUsersPerspective\TargetUsersPerspectiveController@show');

                Route::post('/', 'TargetUsersPerspective\TargetUsersPerspectiveController@store');
                Route::post('/{crm_target_users_perspective}/do/{action}', 'TargetUsersPerspective\TargetUsersPerspectiveController@doAction');

                Route::patch('/{crm_target_users_perspective}', 'TargetUsersPerspective\TargetUsersPerspectiveController@update');
                Route::delete('/{crm_target_users_perspective}', 'TargetUsersPerspective\TargetUsersPerspectiveController@destroy');
            }
        );

        Route::prefix('account-managers-performances-perspective')->group(
            function () {
                Route::get('/', 'AccountManagersPerformancesPerspective\AccountManagersPerformancesPerspectiveController@index');
                Route::get('/actions', 'AccountManagersPerformancesPerspective\AccountManagersPerformancesPerspectiveController@getActions');

                Route::get('{campp}/tags ', 'AccountManagersPerformancesPerspective\AccountManagersPerformancesPerspectiveController@tags');
                Route::post('{campp}/tags ', 'AccountManagersPerformancesPerspective\AccountManagersPerformancesPerspectiveController@saveTags');
                Route::get('{campp}/addresses ', 'AccountManagersPerformancesPerspective\AccountManagersPerformancesPerspectiveController@addresses');
                Route::post('{campp}/addresses ', 'AccountManagersPerformancesPerspective\AccountManagersPerformancesPerspectiveController@saveAddresses');

                Route::get('/{campp}/{subObjects}', 'AccountManagersPerformancesPerspective\AccountManagersPerformancesPerspectiveController@relatedObjects');
                Route::get('/{campp}', 'AccountManagersPerformancesPerspective\AccountManagersPerformancesPerspectiveController@show');

                Route::post('/', 'AccountManagersPerformancesPerspective\AccountManagersPerformancesPerspectiveController@store');
                Route::post('/{campp}/do/{action}', 'AccountManagersPerformancesPerspective\AccountManagersPerformancesPerspectiveController@doAction');

                Route::patch('/{campp}', 'AccountManagersPerformancesPerspective\AccountManagersPerformancesPerspectiveController@update');
                Route::delete('/{campp}', 'AccountManagersPerformancesPerspective\AccountManagersPerformancesPerspectiveController@destroy');
            }
        );

        Route::prefix('quote-items-perspective')->group(
            function () {
                Route::get('/', 'QuoteItemsPerspective\QuoteItemsPerspectiveController@index');
                Route::get('/actions', 'QuoteItemsPerspective\QuoteItemsPerspectiveController@getActions');

                Route::get('{crm_quote_items_perspective}/tags ', 'QuoteItemsPerspective\QuoteItemsPerspectiveController@tags');
                Route::post('{crm_quote_items_perspective}/tags ', 'QuoteItemsPerspective\QuoteItemsPerspectiveController@saveTags');
                Route::get('{crm_quote_items_perspective}/addresses ', 'QuoteItemsPerspective\QuoteItemsPerspectiveController@addresses');
                Route::post('{crm_quote_items_perspective}/addresses ', 'QuoteItemsPerspective\QuoteItemsPerspectiveController@saveAddresses');

                Route::get('/{crm_quote_items_perspective}/{subObjects}', 'QuoteItemsPerspective\QuoteItemsPerspectiveController@relatedObjects');
                Route::get('/{crm_quote_items_perspective}', 'QuoteItemsPerspective\QuoteItemsPerspectiveController@show');

                Route::post('/', 'QuoteItemsPerspective\QuoteItemsPerspectiveController@store');
                Route::post('/{crm_quote_items_perspective}/do/{action}', 'QuoteItemsPerspective\QuoteItemsPerspectiveController@doAction');

                Route::patch('/{crm_quote_items_perspective}', 'QuoteItemsPerspective\QuoteItemsPerspectiveController@update');
                Route::delete('/{crm_quote_items_perspective}', 'QuoteItemsPerspective\QuoteItemsPerspectiveController@destroy');
            }
        );

        Route::prefix('users-perspective')->group(
            function () {
                Route::get('/', 'UsersPerspective\UsersPerspectiveController@index');
                Route::get('/actions', 'UsersPerspective\UsersPerspectiveController@getActions');

                Route::get('{crm_users_perspective}/tags ', 'UsersPerspective\UsersPerspectiveController@tags');
                Route::post('{crm_users_perspective}/tags ', 'UsersPerspective\UsersPerspectiveController@saveTags');
                Route::get('{crm_users_perspective}/addresses ', 'UsersPerspective\UsersPerspectiveController@addresses');
                Route::post('{crm_users_perspective}/addresses ', 'UsersPerspective\UsersPerspectiveController@saveAddresses');

                Route::get('/{crm_users_perspective}/{subObjects}', 'UsersPerspective\UsersPerspectiveController@relatedObjects');
                Route::get('/{crm_users_perspective}', 'UsersPerspective\UsersPerspectiveController@show');

                Route::post('/', 'UsersPerspective\UsersPerspectiveController@store');
                Route::post('/{crm_users_perspective}/do/{action}', 'UsersPerspective\UsersPerspectiveController@doAction');

                Route::patch('/{crm_users_perspective}', 'UsersPerspective\UsersPerspectiveController@update');
                Route::delete('/{crm_users_perspective}', 'UsersPerspective\UsersPerspectiveController@destroy');
            }
        );

        Route::prefix('accounts-summarized-perspective')->group(
            function () {
                Route::get('/', 'AccountsSummarizedPerspective\AccountsSummarizedPerspectiveController@index');
                Route::get('/actions', 'AccountsSummarizedPerspective\AccountsSummarizedPerspectiveController@getActions');

                Route::get('{casp}/tags ', 'AccountsSummarizedPerspective\AccountsSummarizedPerspectiveController@tags');
                Route::post('{casp}/tags ', 'AccountsSummarizedPerspective\AccountsSummarizedPerspectiveController@saveTags');
                Route::get('{casp}/addresses ', 'AccountsSummarizedPerspective\AccountsSummarizedPerspectiveController@addresses');
                Route::post('{casp}/addresses ', 'AccountsSummarizedPerspective\AccountsSummarizedPerspectiveController@saveAddresses');

                Route::get('/{casp}/{subObjects}', 'AccountsSummarizedPerspective\AccountsSummarizedPerspectiveController@relatedObjects');
                Route::get('/{casp}', 'AccountsSummarizedPerspective\AccountsSummarizedPerspectiveController@show');

                Route::post('/', 'AccountsSummarizedPerspective\AccountsSummarizedPerspectiveController@store');
                Route::post('/{casp}/do/{action}', 'AccountsSummarizedPerspective\AccountsSummarizedPerspectiveController@doAction');

                Route::patch('/{casp}', 'AccountsSummarizedPerspective\AccountsSummarizedPerspectiveController@update');
                Route::delete('/{casp}', 'AccountsSummarizedPerspective\AccountsSummarizedPerspectiveController@destroy');
            }
        );

        Route::prefix('opportunities-performance')->group(
            function () {
                Route::get('/', 'OpportunitiesPerformance\OpportunitiesPerformanceController@index');
                Route::get('/actions', 'OpportunitiesPerformance\OpportunitiesPerformanceController@getActions');

                Route::get('{crm_opportunities_performance}/tags ', 'OpportunitiesPerformance\OpportunitiesPerformanceController@tags');
                Route::post('{crm_opportunities_performance}/tags ', 'OpportunitiesPerformance\OpportunitiesPerformanceController@saveTags');
                Route::get('{crm_opportunities_performance}/addresses ', 'OpportunitiesPerformance\OpportunitiesPerformanceController@addresses');
                Route::post('{crm_opportunities_performance}/addresses ', 'OpportunitiesPerformance\OpportunitiesPerformanceController@saveAddresses');

                Route::get('/{crm_opportunities_performance}/{subObjects}', 'OpportunitiesPerformance\OpportunitiesPerformanceController@relatedObjects');
                Route::get('/{crm_opportunities_performance}', 'OpportunitiesPerformance\OpportunitiesPerformanceController@show');

                Route::post('/', 'OpportunitiesPerformance\OpportunitiesPerformanceController@store');
                Route::post('/{crm_opportunities_performance}/do/{action}', 'OpportunitiesPerformance\OpportunitiesPerformanceController@doAction');

                Route::patch('/{crm_opportunities_performance}', 'OpportunitiesPerformance\OpportunitiesPerformanceController@update');
                Route::delete('/{crm_opportunities_performance}', 'OpportunitiesPerformance\OpportunitiesPerformanceController@destroy');
            }
        );

        // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    }
);
