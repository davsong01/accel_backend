<?php

use App\Http\Controllers\MessageTransactionController;
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
Route::get('/verifyenc/{slug}', function($slug){
    return base64_decode($slug);
});
Route::get('/cron-process-messages-transaction', [MessageTransactionController::class, 'processMessagesTransaction']);
Route::get('/', function () {
    // return view('welcome');
});

Route::get('uploads/{filename}', function ($filename) {
    $filename = base64_decode($filename);
    $realpath = base_path() . '/uploads' . '/' . $filename;
    return response()->download($realpath);
});



