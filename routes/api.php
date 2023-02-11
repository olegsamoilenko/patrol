<?php

use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Main\IncidentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    $user = $request->user()->load('roles');
    $user->getAllPermissions();

    return $user;
});

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

    Route::get('/get-districts-pagination', [DistrictController::class, 'getDistrictsPagination']);
    Route::get('/get-all-districts', [DistrictController::class, 'getAllDistricts']);
    Route::post('/add-district', [DistrictController::class, 'addDistrict']);
    Route::post('/edit-district', [DistrictController::class, 'editDistrict']);
    Route::delete('/delete-district/{id}', [DistrictController::class, 'deleteDistrict']);
});

Route::group([
    'namespace' => 'App\Http\Controllers\Main',
    'middleware' => ['auth'],
], static function () {
    Route::get('/get-incident-statistics', [IncidentController::class, 'getIncidentStatistics']);
    Route::get('/get-all-incidents', [IncidentController::class, 'getIncidentsPagination']);
    Route::post('/store-incident', [IncidentController::class, 'storeIncident']);
});
