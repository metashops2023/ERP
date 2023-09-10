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

    Route::group(['prefix' => 'essentials', 'namespace' => 'App\Http\Controllers\Essentials'], function () {

        Route::group(['prefix' => 'workspaces'], function () {
            Route::get('/', 'WorkSpaceController@index')->name('workspace.index');
            Route::get('view/docs/{id}', 'WorkSpaceController@viewDocs')->name('workspace.view.docs');
            Route::get('show/{id}', 'WorkSpaceController@show')->name('workspace.show');
            Route::post('store', 'WorkSpaceController@store')->name('workspace.store');
            Route::get('edit/{id}', 'WorkSpaceController@edit')->name('workspace.edit');
            Route::post('update/{id}', 'WorkSpaceController@update')->name('workspace.update');
            Route::delete('delete/{id}', 'WorkSpaceController@delete')->name('workspace.delete');
            Route::delete('delete/doc/{docId}', 'WorkSpaceController@deleteDoc')->name('workspace.delete.doc');

            Route::group(['prefix' => 'tasks'], function () {
                Route::get('{workspaceId}', 'WorkSpaceTaskController@index')->name('workspace.task.index');
                Route::post('store', 'WorkSpaceTaskController@store')->name('workspace.task.store');
                Route::get('list/{workspaceId}', 'WorkSpaceTaskController@taskList')->name('workspace.task.list');
                Route::get('assign/user/{id}', 'WorkSpaceTaskController@assignUser')->name('workspace.task.assign.user');
                Route::get('change/status/{id}', 'WorkSpaceTaskController@changeStatus')->name('workspace.task.status');
                Route::get('change/priority/{id}', 'WorkSpaceTaskController@changePriority')->name('workspace.task.priority');
                Route::post('update', 'WorkSpaceTaskController@update');
                Route::delete('delete/{id}', 'WorkSpaceTaskController@delete')->name('workspace.task.delete');
            });
        });

        Route::group(['prefix' => 'todo'], function () {
            Route::get('/', 'TodoController@index')->name('todo.index');
            Route::get('show/{id}', 'TodoController@show')->name('todo.show');
            Route::post('store', 'TodoController@store')->name('todo.store');
            Route::get('assign/user/{id}', 'TodoController@assignUser')->name('todo.assign.user');
            Route::get('change/status/modal/{id}', 'TodoController@changeStatusModal')->name('todo.status.modal');
            Route::post('change/status/{id}', 'TodoController@changeStatus')->name('todo.status');
            Route::get('change/priority/{id}', 'TodoController@changePriority')->name('todo.priority');
            Route::get('edit/{id}', 'TodoController@edit')->name('todo.edit');
            Route::post('update/{id}', 'TodoController@update')->name('todo.update');
            Route::delete('delete/{id}', 'TodoController@delete')->name('todo.delete');
        });

        // Route::group(['prefix' => 'documents'], function()
        // {
        //     Route::get('/', 'DocumentController@index')->name('documents.index');
        //     Route::get('show/{id}', 'DocumentController@show')->name('documents.show');
        //     Route::post('store', 'DocumentController@store')->name('documents.store');
        //     Route::get('edit/{id}', 'DocumentController@edit')->name('documents.edit');
        //     Route::post('update/{id}', 'DocumentController@update')->name('documents.update');
        //     Route::delete('delete/{id}', 'DocumentController@delete')->name('documents.delete');
        // });

        Route::group(['prefix' => 'memos'], function () {
            Route::get('/', 'MemoController@index')->name('memos.index');
            Route::get('show/{id}', 'MemoController@show')->name('memos.show');
            Route::post('store', 'MemoController@store')->name('memos.store');
            Route::get('edit/{id}', 'MemoController@edit')->name('memos.edit');
            Route::post('update', 'MemoController@update')->name('memos.update');
            Route::delete('delete/{id}', 'MemoController@delete')->name('memos.delete');
            Route::get('add/user/view/{id}', 'MemoController@addUserView')->name('memos.add.user.view');
            Route::post('add/user/{id}', 'MemoController@addUsers')->name('memos.add.users');
        });

        Route::group(['prefix' => 'messages'], function () {
            Route::get('/', 'MessageController@index')->name('messages.index');
            Route::get('all', 'MessageController@allMessage')->name('messages.all');
            Route::post('store', 'MessageController@store')->name('messages.store');
            Route::delete('delete/{id}', 'MessageController@delete')->name('messages.delete');
        });
    });
});
