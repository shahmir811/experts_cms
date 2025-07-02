<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\BackupsController;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::group(['middleware'=> 'role:super-admin|admin'], function () {   
Route::group(['middleware'=> 'auth'], function () {   


    ///************** Permissions routes **************///
    Route::resource('permissions', PermissionController::class);

    ///************** Roles routes **************///
    Route::resource('roles', RoleController::class);
    Route::get('roles/{slug}/give-permissions', [RoleController::class, 'addPermissionsToRole'])->name('roles.give-permissions');
    Route::put('roles/{slug}/save-permissions', [RoleController::class, 'savePermissionsToRole'])->name('roles.save-permissions');

    ///************** Users routes **************///
    Route::resource('users', UserController::class);    
    // Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    Route::patch('/users/{slug}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');



    ///************** Session routes **************///
    Route::get('active-sessions', [SessionController::class,'index'])->name('active-sessions');
    Route::delete('active-sessions/{id}', [SessionController::class,'destroy'])->name('active-sessions.destroy');

    ///************** Backups routes **************///
    Route::get('/backups', [BackupsController::class, 'index'])->name('backups.index');
    Route::post('/backups/delete', [BackupsController::class, 'delete'])->name('backups.delete');

    ///************** Customers routes **************///
    Route::resource('customers', CustomerController::class);

    ///************** Stores routes **************///
    Route::resource('stores', StoreController::class);

    ///************** Products routes **************///
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/upload', [ProductController::class, 'upload'])->name('products.upload.form');
    Route::post('products/upload', [ProductController::class, 'processUpload'])->name('products.upload');
    Route::get('/products/{product}/detail', [ProductController::class, 'showDetail'])->name('products.detail');


});


