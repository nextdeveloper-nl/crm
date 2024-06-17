<?php

Route::prefix('crm')->group(
    function () {
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

        Route::prefix('emails')->group(
            function () {
                Route::get('/', 'Emails\EmailsController@index');
                Route::get('/actions', 'Emails\EmailsController@getActions');

                Route::get('{crm_emails}/tags ', 'Emails\EmailsController@tags');
                Route::post('{crm_emails}/tags ', 'Emails\EmailsController@saveTags');
                Route::get('{crm_emails}/addresses ', 'Emails\EmailsController@addresses');
                Route::post('{crm_emails}/addresses ', 'Emails\EmailsController@saveAddresses');

                Route::get('/{crm_emails}/{subObjects}', 'Emails\EmailsController@relatedObjects');
                Route::get('/{crm_emails}', 'Emails\EmailsController@show');

                Route::post('/', 'Emails\EmailsController@store');
                Route::post('/{crm_emails}/do/{action}', 'Emails\EmailsController@doAction');

                Route::patch('/{crm_emails}', 'Emails\EmailsController@update');
                Route::delete('/{crm_emails}', 'Emails\EmailsController@destroy');
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

        // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE





















































































































































































































    }
);

















