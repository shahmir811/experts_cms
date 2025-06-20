<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\BackupsController;

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

    Route::get('/backups', [BackupsController::class, 'index'])->name('backups.index');
    Route::post('/backups/delete', [BackupsController::class, 'delete'])->name('backups.delete');

});


