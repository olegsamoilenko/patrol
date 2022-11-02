<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\User\ActivateUserController;
use App\Http\Controllers\Admin\User\DeleteUserController;
use App\Http\Controllers\Admin\User\EditUserController;
use App\Http\Controllers\Admin\User\GetUserPaginationController;
use App\Http\Controllers\Admin\User\GetUserRolesController;
use App\Http\Controllers\Admin\User\GetUserStatisticsController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::group([
//    'namespace' => 'App\Http\Controllers\Admin',
//    'prefix' => 'admin',
//    'middleware' => ['auth'],
// ], static function () {
// //    Route::resource('user', 'UserController');
// //    Route::resource('roles', 'RoleController');
// //    Route::resource('permissions', 'PermissionController');
// //    Route::resource('incident', 'IncidentController');
// });

Route::group([
    'namespace' => 'App\Http\Controllers\Admin',
    'prefix' => 'admin',
    'middleware' => ['auth'],
], static function () {
    Route::get('/get-permissions-pagination', [PermissionController::class, 'getPermissionsPagination']);
    Route::get('/get-all-permissions', [PermissionController::class, 'getAllPermissions']);
    Route::post('/add-permission', [PermissionController::class, 'addPermission']);
    Route::post('/edit-permission-roles', [PermissionController::class, 'editPermissionRoles']);
    Route::delete('/delete-permission/{id}', [PermissionController::class, 'deletePermission']);

    Route::get('/get-roles-pagination', [RoleController::class, 'getRolesPagination']);
    Route::get('/get-all-roles', [RoleController::class, 'getAllRoles']);
    Route::post('/add-role', [RoleController::class, 'addRole']);
    Route::post('/edit-role-permissions', [RoleController::class, 'editRolePermissions']);
    Route::delete('/delete-role/{id}', [RoleController::class, 'deleteRole']);

    Route::get('/get-user-statistics', [UserController::class, 'getUserStatistics']);
    Route::get('/get-users', [UserController::class, 'getUserPagination']);
    Route::post('/activate-user/{id}', [UserController::class, 'activateUser']);
    Route::post('/edit-user/{id}', [UserController::class, 'editUser']);
    Route::delete('/delete-user/{id}', [UserController::class, 'deleteUser']);
});


Route::get('/{catchall?}', static function () {
    return view('layouts.app');
})->where('catchall', '.*');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
