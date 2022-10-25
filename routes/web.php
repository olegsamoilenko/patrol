<?php

use App\Http\Controllers\Admin\User\ActivateUserController;
use App\Http\Controllers\Admin\User\DeleteUserController;
use App\Http\Controllers\Admin\User\EditUserRolesController;
use App\Http\Controllers\Admin\User\GetUserPaginationController;
use App\Http\Controllers\Admin\User\GetUserRolesController;
use App\Http\Controllers\Admin\User\GetUserStatisticsController;
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
Route::get('/get-user-statistics', [GetUserStatisticsController::class, 'getUserStatistics']);
Route::get('/get-users', [GetUserPaginationController::class, 'getUserPagination']);
Route::get('/get-user-roles', [GetUserRolesController::class, 'getUserRoles']);
Route::post('/activate-user/{id}', [ActivateUserController::class, 'activateUser']);
Route::post('/edit-user-roles', [EditUserRolesController::class, 'editUserRoles']);
Route::delete('/delete-user/{id}', [DeleteUserController::class, 'deleteUser']);

Route::get('/{catchall?}', static function () {
    return view('layouts.app');
})->where('catchall', '.*');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
