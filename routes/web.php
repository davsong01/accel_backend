<?php

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\CustomerContract;
use App\Mail\MessageTransactionMail;
use Illuminate\Support\Facades\Route;
use App\Models\GeneratedLegalDocument;
use App\Http\Controllers\GarbageController;
use App\Http\Controllers\MessageTransactionController;

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
Route::get('truncate', [GarbageController::class, 'clearCustomerData']);

Route::get('update-generated-legal-documents', function(){
    $generated = GeneratedLegalDocument::with('template')->whereNull('legal_document_type_id')->get();
    if($generated->count() > 0){
        foreach($generated as $document){
            $document->legal_document_type_id = $document->template->legalDocumentType->id;
            $document->save();
        }
    }else{
        dd('All Done!');
    }
});

Route::get('/testfile', function () {
});

Route::get('/testreceipttemplate', function () {
    $data = [];
    $data['company_id'] = 1;
    $data['receipt_no'] = '0001';

    $payment = App\Models\ContractPayment::find(12);
    $contract = CustomerContract::where('id', 165)->first();
    
    return view('receipts.template1', compact('data', 'payment', 'contract'));
});

Route::get('/testreceiptdownload/{payment_id}/{contract_id}/{user_id}', function ($payment_id, $contract_id, $user_id) {
    $data = [];
    $data['company_id'] = 1;
    $data['receipt_no'] = '0001';

    $payment = App\Models\ContractPayment::find($payment_id);
    $contract = CustomerContract::with('customer')->where('id', $contract_id)->first();
    $name = $transaction_id ?? $payment->transaction_id;
    $data['receipt_no'] = $name;
    $payment->customer = App\Models\User::where('id', $user_id)->first();
    
    $pathToSave = app('App\Http\Controllers\Controller')->getPathToSaveFile('receipts') . '/' . $name . '.pdf';
    $others = [
        'contract_value' => $payment->contract_value,
        'discount_applied' => $payment->discount_applied,
    ];

    $pdf = PDF::loadView('receipts.test-payment', compact('payment', 'data', 'contract', 'others'));
    return $pdf->download('test-receipt-document.pdf');

    file_put_contents(base_path() . '/' . config('app.upload_path') . '/' . $pathToSave, $pdf->output());

    // for snappy
    // $pdf = PDF::loadView('pdf_view', $data);
    // return $pdf->download('document.pdf');

    return [
        'receipt_no' => $name . '.pdf',
        'filename' => $name . '.pdf',
        'filepath' => config('app.upload_path') . '/' . $pathToSave
    ];
    return view('receipts.template1', compact('data', 'payment', 'contract'));
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
        return response()->file($realpath);
        //code...
    } catch (\Throwable $th) {
        return response()->json([
            'error' => 'The file does not exist',
            'data' => [],
        ], 200);
    }
});
