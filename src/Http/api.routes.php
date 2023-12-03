<?php

Route::prefix('crm')->group(
    function () {
        Route::prefix('account-managers')->group(
            function () {
                Route::get('/', 'AccountManagers\AccountManagersController@index');
                Route::get('/{crm_account_managers}', 'AccountManagers\AccountManagersController@show');
                Route::get('/{crm_account_managers}/{subObjects}', 'AccountManagers\AccountManagersController@subObjects');
                Route::post('/', 'AccountManagers\AccountManagersController@store');
                Route::patch('/{crm_account_managers}', 'AccountManagers\AccountManagersController@update');
                Route::delete('/{crm_account_managers}', 'AccountManagers\AccountManagersController@destroy');
            }
        );

        Route::prefix('accounts')->group(
            function () {
                Route::get('/', 'Accounts\AccountsController@index');
                Route::get('/{crm_accounts}', 'Accounts\AccountsController@show');
                Route::get('/{crm_accounts}/{subObjects}', 'Accounts\AccountsController@subObjects');
                Route::post('/', 'Accounts\AccountsController@store');
                Route::patch('/{crm_accounts}', 'Accounts\AccountsController@update');
                Route::delete('/{crm_accounts}', 'Accounts\AccountsController@destroy');
            }
        );

        Route::prefix('opportunities')->group(
            function () {
                Route::get('/', 'Opportunities\OpportunitiesController@index');
                Route::get('/{crm_opportunities}', 'Opportunities\OpportunitiesController@show');
                Route::get('/{crm_opportunities}/{subObjects}', 'Opportunities\OpportunitiesController@subObjects');
                Route::post('/', 'Opportunities\OpportunitiesController@store');
                Route::patch('/{crm_opportunities}', 'Opportunities\OpportunitiesController@update');
                Route::delete('/{crm_opportunities}', 'Opportunities\OpportunitiesController@destroy');
            }
        );

        Route::prefix('quotes')->group(
            function () {
                Route::get('/', 'Quotes\QuotesController@index');
                Route::get('/{crm_quotes}', 'Quotes\QuotesController@show');
                Route::get('/{crm_quotes}/{subObjects}', 'Quotes\QuotesController@subObjects');
                Route::post('/', 'Quotes\QuotesController@store');
                Route::patch('/{crm_quotes}', 'Quotes\QuotesController@update');
                Route::delete('/{crm_quotes}', 'Quotes\QuotesController@destroy');
            }
        );

        Route::prefix('user-managers')->group(
            function () {
                Route::get('/', 'UserManagers\UserManagersController@index');
                Route::get('/{crm_user_managers}', 'UserManagers\UserManagersController@show');
                Route::get('/{crm_user_managers}/{subObjects}', 'UserManagers\UserManagersController@subObjects');
                Route::post('/', 'UserManagers\UserManagersController@store');
                Route::patch('/{crm_user_managers}', 'UserManagers\UserManagersController@update');
                Route::delete('/{crm_user_managers}', 'UserManagers\UserManagersController@destroy');
            }
        );

        Route::prefix('users')->group(
            function () {
                Route::get('/', 'Users\UsersController@index');
                Route::get('/{crm_users}', 'Users\UsersController@show');
                Route::get('/{crm_users}/{subObjects}', 'Users\UsersController@subObjects');
                Route::post('/', 'Users\UsersController@store');
                Route::patch('/{crm_users}', 'Users\UsersController@update');
                Route::delete('/{crm_users}', 'Users\UsersController@destroy');
            }
        );

        // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
    }
);





