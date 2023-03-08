<?php

use App\Http\Controllers\Admin\BattalionController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PlatoonController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Main\IncidentController;
use App\Http\Controllers\MainPageIncidentController;
use App\Http\Controllers\SummaryController;
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
    Route::delete('/soft-delete-user/{id}', [UserController::class, 'softDeleteUser']);
    Route::delete('/delete-user/{id}', [UserController::class, 'deleteUser']);

    Route::get('/get-districts-pagination', [DistrictController::class, 'getDistrictsPagination']);
    Route::get('/get-all-districts', [DistrictController::class, 'getAllDistricts']);
    Route::post('/add-district', [DistrictController::class, 'addDistrict']);
    Route::post('/edit-district', [DistrictController::class, 'editDistrict']);
    Route::delete('/delete-district/{id}', [DistrictController::class, 'deleteDistrict']);

    Route::get('/get-feedbacks-pagination', [FeedbackController::class, 'getFeedbacksPagination']);
    Route::post('/edit-feedback/{id}', [FeedbackController::class, 'editFeedback']);
    Route::delete('/delete-feedback/{id}', [FeedbackController::class, 'deleteFeedback']);
});

Route::group([
    'namespace' => 'App\Http\Controllers\Main',
    'middleware' => ['auth'],
], static function () {
    Route::get('/get-incident-statistics', [IncidentController::class, 'getIncidentStatistics']);
    Route::get('/get-summary', [SummaryController::class, 'getSummary']);
    Route::post('/edit-summary', [SummaryController::class, 'editSummary']);

    Route::get('/get-all-incidents', [IncidentController::class, 'getIncidentsPagination']);
    Route::post('/store-incident', [IncidentController::class, 'storeIncident']);
    Route::post('/edit-incident/{id}', [IncidentController::class, 'editIncident']);
    Route::delete('/soft-delete-incident/{id}', [IncidentController::class, 'softDeleteIncident']);
    Route::delete('/delete-incident/{id}', [IncidentController::class, 'deleteIncident']);

    Route::get('/get-all-main-page-incidents', [MainPageIncidentController::class, 'getIncidents']);
    Route::post('/store-main-page-incident', [MainPageIncidentController::class, 'storeIncident']);
    Route::post('/edit-main-page-incident/{id}', [MainPageIncidentController::class, 'editIncident']);
    Route::delete('/delete-main-page-incident/{id}', [MainPageIncidentController::class, 'deleteIncident']);

    Route::post('/store-feedback', [FeedbackController::class, 'storeFeedback']);

    Route::get('/get-all-chats', [ChatController::class, 'getAllChats']);
    Route::post('/store-chat', [ChatController::class, 'storeChat']);
});
