<?php

use Illuminate\Support\Facades\Route;

Route::get('route-list', function () {
    if (env('APP_DEBUG') === true) {
        Artisan::call('route:list --columns=Method,URI,Name,Action');
        return '<pre>' . Artisan::output() . '</pre>';
    } else {
        echo '<h1>Access Denied</h1>';
        return null;
    }
});

Route::get('add-user', function () {
    $addAdmin = new AdminAndUser();
    $addAdmin->prefix = 'Mr.';
    $addAdmin->name = 'Super';
    $addAdmin->last_name = 'Admin';
    $addAdmin->email = 'superadmin@gmail.com';
    $addAdmin->username = 'superadmin';
    $addAdmin->password = Hash::make('12345');
    $addAdmin->role_type = 3;
    $addAdmin->role_permission_id = 1;
    $addAdmin->allow_login = 1;
    $addAdmin->save();
    //1=super_admin;2=admin;3=Other;
});

Route::get('/test', function () {
    //return str_pad(10, 10, "0", STR_PAD_LEFT);
    // $purchases = Purchase::all();
    // foreach ($purchases as $p) {
    //     $p->is_last_created = 0;
    //     $p->save();
    // }
   
    
});

// Route::get('dbal', function() {
//     dd(\Doctrine\DBAL\Types\Type::getTypesMap());
// });
