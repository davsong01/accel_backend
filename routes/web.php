<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
});

Route::get('uploads/{filename}', function ($filename) {
    $filename = base64_decode($filename);
    $realpath = base_path() . '/uploads' . '/' . $filename;
    return response()->download($realpath);
});

Route::middleware(['auth:sanctum', 'company_check', 'user_roles', 'otherchecks'])->group(function () {
    Route::get('documents/{filename}', function ($filename) {
        $filename = base64_decode($filename);
        $realpath = base_path() . '/documents' . '/' . $filename;
        return response()->download($realpath);
    });
});

