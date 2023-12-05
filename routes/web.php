<?php

use App\Http\Controllers\MessageTransactionController;
use App\Mail\MessageTransactionMail;
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
Route::get('/tests', function(){
    $data['message'] = 'helllosds';
    $data['company_id'] = 1;
    
    return new App\Mail\OrderShipped($data);
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



