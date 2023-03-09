<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Main\IncidentController;
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

Route::get('/{catchall?}', [HomeController::class, 'index'])->where('catchall', '.*');
Route::patch('/fcm-token', [HomeController::class, 'updateToken'])->name('fcmToken');
Route::post('/send-notification',[HomeController::class,'notification'])->name('notification');

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
