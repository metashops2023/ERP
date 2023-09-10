<?php

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\CheckTenantForMaintenanceMode;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    // InitializeTenancyByPath::class,
    PreventAccessFromCentralDomains::class,
    CheckTenantForMaintenanceMode::class
])->group(function () {

    Route::group(['prefix' => 'manufacturing', 'namespace' => 'App\Http\Controllers\Manufacturing'], function () {
        Route::group(['prefix' => 'process'], function () {
            Route::get('/', 'ProcessController@index')->name('manufacturing.process.index');
            Route::get('show/{processId}', 'ProcessController@show')->name('manufacturing.process.show');
            Route::get('create', 'ProcessController@create')->name('manufacturing.process.create');
            Route::post('store', 'ProcessController@store')->name('manufacturing.process.store');
            Route::get('edit/{processId}', 'ProcessController@edit')->name('manufacturing.process.edit');
            Route::post('update/{processId}', 'ProcessController@update')->name('manufacturing.process.update');
            Route::delete('delete/{processId}', 'ProcessController@delete')->name('manufacturing.process.delete');
        });

        Route::group(['prefix' => 'productions'], function () {
            Route::get('/', 'ProductionController@index')->name('manufacturing.productions.index');
            Route::get('show/{productionId}', 'ProductionController@show')->name('manufacturing.productions.show');
            Route::get('create', 'ProductionController@create')->name('manufacturing.productions.create');
            Route::post('store', 'ProductionController@store')->name('manufacturing.productions.store');
            Route::get('edit/{productionId}', 'ProductionController@edit')->name('manufacturing.productions.edit');
            Route::post('update/{productionId}', 'ProductionController@update')->name('manufacturing.productions.update');
            Route::delete('delete/{productionId}', 'ProductionController@delete')->name('manufacturing.productions.delete');
            Route::get('get/process/{processId}', 'ProductionController@getProcess');
            Route::get('get/ingredients/{processId}/{warehouseId}', 'ProductionController@getIngredients');
        });

        Route::group(['prefix' => 'settings'], function () {
            Route::get('/', 'SettingsController@index')->name('manufacturing.settings.index');
            Route::post('store', 'SettingsController@store')->name('manufacturing.settings.store');
        });

        Route::group(['prefix' => 'report'], function () {
            Route::get('/', 'ReportController@index')->name('manufacturing.report.index');
        });
    });
});
