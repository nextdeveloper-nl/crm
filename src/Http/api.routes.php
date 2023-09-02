<?php

Route::prefix('crm')->group(function() {
Route::prefix('accounts')->group(function () {
        Route::get('/', 'CrmAccount\CrmAccountController@index');
        Route::get('/{crm_accounts}', 'CrmAccount\CrmAccountController@show');
        Route::post('/', 'CrmAccount\CrmAccountController@store');
        Route::patch('/{crm_accounts}', 'CrmAccount\CrmAccountController@update');
        Route::delete('/{crm_accounts}', 'CrmAccount\CrmAccountController@destroy');
    });

Route::prefix('opportunities')->group(function () {
        Route::get('/', 'CrmOpportunity\CrmOpportunityController@index');
        Route::get('/{crm_opportunities}', 'CrmOpportunity\CrmOpportunityController@show');
        Route::post('/', 'CrmOpportunity\CrmOpportunityController@store');
        Route::patch('/{crm_opportunities}', 'CrmOpportunity\CrmOpportunityController@update');
        Route::delete('/{crm_opportunities}', 'CrmOpportunity\CrmOpportunityController@destroy');
    });

// EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE\n\n
});