<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\EstateController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PlotSizeController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);

Route::group(['prefix' => 'admin', 'before' => 'admin'], function () {
    // Route::post('/login', function () {
    //     dd('sds');
    // });
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::middleware(['auth:sanctum', 'company_check', 'user_roles', 'otherchecks'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::get('/permissions', [MenuController::class, 'linkGenerator'])->name('permissions.index');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Admin Module Routes
        Route::resource('customers', CustomerController::class);
        Route::resource('city', CityController::class);
        Route::resource('location', LocationController::class);
        Route::resource('plotsize', PlotSizeController::class);
        Route::resource('estate', EstateController::class);
        Route::resource('estate', EstateController::class);
        Route::get('estate-count/{estate_id}/{search?}', [EstateController::class, 'filterPropertyCount']);
        Route::post('update-estate-property-type/{id}', [EstateController::class, 'updateEstatePropertyType']);
        Route::get('change-estate-property-type-status/{id}', [EstateController::class, 'changeEstatePropertyType']);
    });
});
