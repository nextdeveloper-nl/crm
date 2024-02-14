<?php

Route::prefix('crm')->group(
    function () {
        Route::prefix('account-managers')->group(
            function () {
                Route::get('/', 'AccountManagers\AccountManagersController@index');

                Route::get('{crm_account_managers}/tags ', 'AccountManagers\AccountManagersController@tags');
                Route::post('{crm_account_managers}/tags ', 'AccountManagers\AccountManagersController@saveTags');
                Route::get('{crm_account_managers}/addresses ', 'AccountManagers\AccountManagersController@addresses');
                Route::post('{crm_account_managers}/addresses ', 'AccountManagers\AccountManagersController@saveAddresses');

                Route::get('/{crm_account_managers}/{subObjects}', 'AccountManagers\AccountManagersController@relatedObjects');
                Route::get('/{crm_account_managers}', 'AccountManagers\AccountManagersController@show');

                Route::post('/', 'AccountManagers\AccountManagersController@store');
                Route::patch('/{crm_account_managers}', 'AccountManagers\AccountManagersController@update');
                Route::delete('/{crm_account_managers}', 'AccountManagers\AccountManagersController@destroy');
            }
        );

        Route::prefix('accounts')->group(
            function () {
                Route::get('/', 'Accounts\AccountsController@index');

                Route::get('{crm_accounts}/tags ', 'Accounts\AccountsController@tags');
                Route::post('{crm_accounts}/tags ', 'Accounts\AccountsController@saveTags');
                Route::get('{crm_accounts}/addresses ', 'Accounts\AccountsController@addresses');
                Route::post('{crm_accounts}/addresses ', 'Accounts\AccountsController@saveAddresses');

                Route::get('/{crm_accounts}/{subObjects}', 'Accounts\AccountsController@relatedObjects');
                Route::get('/{crm_accounts}', 'Accounts\AccountsController@show');

                Route::post('/', 'Accounts\AccountsController@store');
                Route::patch('/{crm_accounts}', 'Accounts\AccountsController@update');
                Route::delete('/{crm_accounts}', 'Accounts\AccountsController@destroy');
            }
        );

        Route::prefix('users')->group(
            function () {
                Route::get('/', 'Users\UsersController@index');

                Route::get('{crm_users}/tags ', 'Users\UsersController@tags');
                Route::post('{crm_users}/tags ', 'Users\UsersController@saveTags');
                Route::get('{crm_users}/addresses ', 'Users\UsersController@addresses');
                Route::post('{crm_users}/addresses ', 'Users\UsersController@saveAddresses');

                Route::get('/{crm_users}/{subObjects}', 'Users\UsersController@relatedObjects');
                Route::get('/{crm_users}', 'Users\UsersController@show');

                Route::post('/', 'Users\UsersController@store');
                Route::patch('/{crm_users}', 'Users\UsersController@update');
                Route::delete('/{crm_users}', 'Users\UsersController@destroy');
            }
        );

        Route::prefix('quotes')->group(
            function () {
                Route::get('/', 'Quotes\QuotesController@index');

                Route::get('{crm_quotes}/tags ', 'Quotes\QuotesController@tags');
                Route::post('{crm_quotes}/tags ', 'Quotes\QuotesController@saveTags');
                Route::get('{crm_quotes}/addresses ', 'Quotes\QuotesController@addresses');
                Route::post('{crm_quotes}/addresses ', 'Quotes\QuotesController@saveAddresses');

                Route::get('/{crm_quotes}/{subObjects}', 'Quotes\QuotesController@relatedObjects');
                Route::get('/{crm_quotes}', 'Quotes\QuotesController@show');

                Route::post('/', 'Quotes\QuotesController@store');
                Route::patch('/{crm_quotes}', 'Quotes\QuotesController@update');
                Route::delete('/{crm_quotes}', 'Quotes\QuotesController@destroy');
            }
        );

        Route::prefix('user-managers')->group(
            function () {
                Route::get('/', 'UserManagers\UserManagersController@index');

                Route::get('{crm_user_managers}/tags ', 'UserManagers\UserManagersController@tags');
                Route::post('{crm_user_managers}/tags ', 'UserManagers\UserManagersController@saveTags');
                Route::get('{crm_user_managers}/addresses ', 'UserManagers\UserManagersController@addresses');
                Route::post('{crm_user_managers}/addresses ', 'UserManagers\UserManagersController@saveAddresses');

                Route::get('/{crm_user_managers}/{subObjects}', 'UserManagers\UserManagersController@relatedObjects');
                Route::get('/{crm_user_managers}', 'UserManagers\UserManagersController@show');

                Route::post('/', 'UserManagers\UserManagersController@store');
                Route::patch('/{crm_user_managers}', 'UserManagers\UserManagersController@update');
                Route::delete('/{crm_user_managers}', 'UserManagers\UserManagersController@destroy');
            }
        );

        Route::prefix('opportunities')->group(
            function () {
                Route::get('/', 'Opportunities\OpportunitiesController@index');

                Route::get('{crm_opportunities}/tags ', 'Opportunities\OpportunitiesController@tags');
                Route::post('{crm_opportunities}/tags ', 'Opportunities\OpportunitiesController@saveTags');
                Route::get('{crm_opportunities}/addresses ', 'Opportunities\OpportunitiesController@addresses');
                Route::post('{crm_opportunities}/addresses ', 'Opportunities\OpportunitiesController@saveAddresses');

                Route::get('/{crm_opportunities}/{subObjects}', 'Opportunities\OpportunitiesController@relatedObjects');
                Route::get('/{crm_opportunities}', 'Opportunities\OpportunitiesController@show');

                Route::post('/', 'Opportunities\OpportunitiesController@store');
                Route::patch('/{crm_opportunities}', 'Opportunities\OpportunitiesController@update');
                Route::delete('/{crm_opportunities}', 'Opportunities\OpportunitiesController@destroy');
            }
        );

        Route::prefix('accounts-perspective')->group(
            function () {
                Route::get('/', 'AccountsPerspective\AccountsPerspectiveController@index');

                Route::get('{crm_accounts_perspective}/tags ', 'AccountsPerspective\AccountsPerspectiveController@tags');
                Route::post('{crm_accounts_perspective}/tags ', 'AccountsPerspective\AccountsPerspectiveController@saveTags');
                Route::get('{crm_accounts_perspective}/addresses ', 'AccountsPerspective\AccountsPerspectiveController@addresses');
                Route::post('{crm_accounts_perspective}/addresses ', 'AccountsPerspective\AccountsPerspectiveController@saveAddresses');

                Route::get('/{crm_accounts_perspective}/{subObjects}', 'AccountsPerspective\AccountsPerspectiveController@relatedObjects');
                Route::get('/{crm_accounts_perspective}', 'AccountsPerspective\AccountsPerspectiveController@show');

                Route::post('/', 'AccountsPerspective\AccountsPerspectiveController@store');
                Route::patch('/{crm_accounts_perspective}', 'AccountsPerspective\AccountsPerspectiveController@update');
                Route::delete('/{crm_accounts_perspective}', 'AccountsPerspective\AccountsPerspectiveController@destroy');
            }
        );

        Route::prefix('users-perspective')->group(
            function () {
                Route::get('/', 'UsersPerspective\UsersPerspectiveController@index');

                Route::get('{crm_users_perspective}/tags ', 'UsersPerspective\UsersPerspectiveController@tags');
                Route::post('{crm_users_perspective}/tags ', 'UsersPerspective\UsersPerspectiveController@saveTags');
                Route::get('{crm_users_perspective}/addresses ', 'UsersPerspective\UsersPerspectiveController@addresses');
                Route::post('{crm_users_perspective}/addresses ', 'UsersPerspective\UsersPerspectiveController@saveAddresses');

                Route::get('/{crm_users_perspective}/{subObjects}', 'UsersPerspective\UsersPerspectiveController@relatedObjects');
                Route::get('/{crm_users_perspective}', 'UsersPerspective\UsersPerspectiveController@show');

                Route::post('/', 'UsersPerspective\UsersPerspectiveController@store');
                Route::patch('/{crm_users_perspective}', 'UsersPerspective\UsersPerspectiveController@update');
                Route::delete('/{crm_users_perspective}', 'UsersPerspective\UsersPerspectiveController@destroy');
            }
        );

        // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE







































































































































    }
);



















