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

Route::get('/testemailtemplate', function () {
    $data['message'] = 'helllosds';
    $data['company_id'] = 1;

    return new App\Mail\OrderShipped($data);
});

Route::get('/testfile', function () {
});

Route::get('/testreceipttemplate', function () {
    $data = [];
    $data['company_id'] = 1;
    $data['receipt_no'] = '0001';
    $payment = App\Models\Transaction::find(24);
    return view('receipts.template1', compact('data', 'payment'));
});

Route::get('/cron-process-messages-transaction', [MessageTransactionController::class, 'processMessagesTransaction']);
Route::get('/', function () {
    // return view('welcome');
});

Route::get('login', function () {
    return response()->json(['error' => 'Unauthenticated.'], 401);
})->name('login');

Route::get('general-uploads/{filename}', function ($filename) {
    $filename = base64_decode($filename);
    $realpath = base_path() . '/' . $filename;

    try {
        return response()->download($realpath);
        //code...
    } catch (\Throwable $th) {
        return response()->json([
            'error' => 'The file does not exist',
            'data' => [],
        ], 200);
    }
});
