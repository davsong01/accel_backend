<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
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
    
    Route::middleware(['auth:sanctum', 'company_check', 'user_roles'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::get('/permissions', [MenuController::class, 'linkGenerator']);
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });
});
