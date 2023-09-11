<?php

Route::prefix('crm')->group(
    function () {
        Route::prefix('accounts')->group(
            function () {
                Route::get('/', 'Accounts\AccountsController@index');
                Route::get('/{crm_accounts}', 'Accounts\AccountsController@show');
                Route::post('/', 'Accounts\AccountsController@store');
                Route::patch('/{crm_accounts}', 'Accounts\AccountsController@update');
                Route::delete('/{crm_accounts}', 'Accounts\AccountsController@destroy');
            }
        );

        Route::prefix('opportunities')->group(
            function () {
                Route::get('/', 'Opportunities\OpportunitiesController@index');
                Route::get('/{crm_opportunities}', 'Opportunities\OpportunitiesController@show');
                Route::post('/', 'Opportunities\OpportunitiesController@store');
                Route::patch('/{crm_opportunities}', 'Opportunities\OpportunitiesController@update');
                Route::delete('/{crm_opportunities}', 'Opportunities\OpportunitiesController@destroy');
            }
        );

        Route::prefix('users')->group(
            function () {
                Route::get('/', 'Users\UsersController@index');
                Route::get('/{crm_users}', 'Users\UsersController@show');
                Route::post('/', 'Users\UsersController@store');
                Route::patch('/{crm_users}', 'Users\UsersController@update');
                Route::delete('/{crm_users}', 'Users\UsersController@destroy');
            }
        );

        // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n
    }
);