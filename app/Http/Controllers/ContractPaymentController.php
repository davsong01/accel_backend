<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\ContractPayment;
use App\Models\CustomerContract;
use App\Models\SeInfrastructure;
use App\Models\ContractPaymentThread;
use App\Models\SeInfrastructureThread;
use App\Services\LogService;
use Illuminate\Support\Facades\Validator;

class ContractPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    public function contractPaymentHistory($contract_id)
    {
        $paymentHistory = ContractPayment::where('contract_id', $contract_id)->first();
        $history = [
            'contract_id' => $contract_id,
            'payment_history' => $paymentHistory?->payment_thread,
        ];

        return response()->json(['message' => 'Success', 'data' => $history], 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    public function contractPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_id' => 'required|exists:chart_of_accounts,id,company_id,' . $this->sessionDetails('company_id'),
            'customer_id' => 'required|exists:customers,id,company_id,' . $this->sessionDetails('company_id'),
            'contract_ids' => 'required',
            'branch_id' => 'nullable|exists:branches,id,company_id,' . $this->sessionDetails('company_id'),
            'description' => 'nullable',
            'payment_slip' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $contract_ids = json_decode($request['contract_ids'], true);

        if (count($contract_ids) < 1) {
            return response()->json([
                'error' => 'Please select one or more contracts to pay for',
                'data' => null,
            ], 412);
        }

        $payments = [];

        // Took this outside the loop to reduce db calls
        $allContractsX = CustomerContract::whereIn('id', array_column($contract_ids, 'contract_id'))->where('customer_id', $request->customer_id)->where('company_id', $this->sessionDetails('company_id'))->get();

        foreach ($contract_ids as $contract) {
            // 'required|exists:customer_contracts,id,form_approval_status,approved,company_id,' . $this->sessionDetails('company_id'),
            //     'customer_id',
            
            //if customer is confirmed(if customer form_status == confirmed ) and contract offer letter has been generated
            $contract_validator = Validator::make($contract, [
                'allow_installment' => 'required|In:0,1',
                'no_of_months' => 'nullable',
                'amount' => 'required',
                'contract_id' =>
                
                $request->customer_id,
                'contract_id' => 'required|exists:customer_contracts,id,form_approval_status,approved,company_id,' . $this->sessionDetails('company_id'), 'customer_id', $request->customer_id,
            ]);

            if ($contract_validator->fails()) {
                return response()->json([
                    'error' => $contract_validator->messages()->first(),
                    'data' => null,
                ], 412);
            }

            $contractX = $allContractsX->where('id', $contract['contract_id'])->first();

            $contract = array_merge($contract, $request->all());

            $res = $this->processContractPayment($contractX, $contract);

            if (isset($res['status']) && $res['status'] == 'error') {
                return response()->json(['error' => $res['message'], 'data' => []], 200);
            }

            $payments[] = [
                'contract_id' => $contractX->id,
                'contract_name' => $contractX->contract_name,
                'contract_value' => $res['payment']->contract_value,
                'discount_applied' => $res['payment']->discount_applied,
                'property_net_fee' => $res['payment']->property_net_fee,
                'transaction_id' => $res['payment']->transaction_id,
                'amount' => $res['payment']->amount,
                'description' => $res['payment']->description,
                'balance' => $res['payment']->balance,
                'no_of_months' => $res['payment']->no_of_months,
                'payment_slip' => $res['payment']->filename,
                'receipt_location' => $res['receipt_location'] ?? null,
            ];
        }

        // Log Email of receipt and provisional letter
        $this->logContractEmail($contractX, $payments);

        LogService::admin(auth()->user()->id,  $contractX->customer->user->id, 'Update a company\'s contract, contract payment, contract payment thread and transaction (' . $contractX->id . ')', 'UPDATE');
        
        return response()->json(['message' => 'Payment successful', 'data' => $contractX], 200);
    }
    
    public function continueContractPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_id' => 'required|exists:chart_of_accounts,id,company_id,' . $this->sessionDetails('company_id'),
            'customer_id' => 'required|exists:customers,id,company_id,' . $this->sessionDetails('company_id'),
            'payment_slip' => 'nullable',
            'contract_ids' => 'required',
            'description' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $contract_ids = json_decode($request['contract_ids'], true);

        if (count($contract_ids) < 1) {
            return response()->json([
                'error' => 'Please select one or more contracts to pay for',
                'data' => null,
            ], 412);
        }

        $payments = [];
        $allContractsX = CustomerContract::whereIn('id', array_column($contract_ids, 'contract_id'))->where('customer_id', $request->customer_id)->where('company_id', $this->sessionDetails('company_id'))->get();
        
        $allPaymentX = ContractPayment::whereIn('contract_id', array_column($contract_ids, 'contract_id'))->get();
        
        foreach ($contract_ids as $contract_id) {
            $contract_validator = Validator::make($contract_id, [
                'amount' => 'required',
                'contract_id' => 'required|exists:customer_contracts,id,form_approval_status,approved,company_id,' . $this->sessionDetails('company_id'), 'customer_id', $request->customer_id,
            ]);

            if ($contract_validator->fails()) {
                return response()->json([
                    'error' => $contract_validator->messages()->first(),
                    'data' => null,
                ], 412);
            }

            $contractX = $allContractsX->where('id', $contract_id['contract_id'])->first();
            $payment = $allPaymentX->where('contract_id', $contract_id['contract_id'])->first();
            
            if(!$payment){
                return response()->json(['error' => 'Previous Payment for Contract: '. $contractX->contract_name. ' not found, Please make a payment', 'data' => []], 200);
            }
            
            if ($payment->balance == 0) {
                return response()->json([
                    'error' => 'Full payment has already been made for : ' . $contractX->contract_name,
                    'data' => null,
                ], 422);
            }
            $request['amount'] = $contract_id['amount'];
            
            $res = $this->processContinueContractPayment($contractX, $request->all(), $payment);
            
            if (isset($res['status']) && $res['status'] == 'error') {
                return response()->json(['error' => $res['message'], 'data' => []], 412);
            }
            
            $payments[] = [
                'contract_id' => $contractX->id,
                'contract_name' => $contractX->contract_name,
                'contract_value' => $res['payment']->contract_value,
                'discount_applied' => $res['payment']->discount_applied,
                'property_net_fee' => $res['payment']->property_net_fee,
                'transaction_id' => $res['payment']->transaction_id,
                'amount' => $res['payment']->amount,
                'description' => $res['payment']->description,
                'balance' => $res['payment']->balance,
                'no_of_months' => $res['payment']->no_of_months,
                'payment_slip' => $res['payment']->filename,
                'receipt_location' => $res['receipt_location'] ?? null,
            ];
        }
        
        // Log email
        $this->logContractEmail($contractX, $payments);
        
        LogService::admin(auth()->user()->id,  $contractX?->customer?->user?->id, 'Update a company\'s contract, contract payment, contract payment thread and transaction (' . $contractX->id . ')', 'UPDATE');
        
        return response()->json(['message' => 'Payment successful', 'data' => $contractX,], 200);
    }

    public function logContractEmail($contract, $payments)
    {
        // Log Email of receipt and provisional letter

        // if ($contract->contract_status == 'ongoing') {
        //     $data['type'] = 'contract.payment.part';
        // } else {
        //     $data['type'] = 'contract.payment';
        // }

        $data['type'] = 'contract.payment';
        $data['contract_ids'] = [$contract->id];
        $data['name'] =  $contract->customer->user->first_name;
        $data['email'] =  $contract->customer->user->email;
        $data['attachments'] = array_column($payments, 'receipt_location');;

        $this->logEmail($data);
    }

    public function processContinueContractPayment($contract, $data, $payment)
    {
        \DB::beginTransaction();

        try {
            $transaction_id = $this->generateTransactionId('CTB');
            if (!empty($data['payment_slip'])) {
                $filename = $this->uploadFile($data['payment_slip'], 'payments');
            } else {
                $filename = null;
            }

            $amount = $data['amount'];
            $fee = $payment->property_net_fee;

            $balance = $fee - ($data['amount'] + $payment->amount);

            if ($amount > $payment->balance) {
                \DB::rollback();

                return [
                    'status' => 'error',
                    'message' => 'You cannot pay more than the balance of : ' . $payment->balance . ' for this contract',
                ];
            }


            $balance = ($balance < 0) ? 0 : $balance;
            $others['contract_value'] = $payment->contract_value;
            $others['discount_applied'] = $payment->discount_applied;
            
            // Create Payment thread
            $payment_thread = ContractPaymentThread::create([
                'contract_payments_id' => $payment->id,
                'amount' => $amount,
                'transaction_id' => $transaction_id,
                'payment_slip' => $filename,
                'account_id' => $data['account_id'],
                'branch_id' => $payment->branch_id,
                'description' => $data['description'],
                'customer_id' => $data['customer_id'],
                'company_id' => $this->sessionDetails('company_id')
            ]);

            $totalAmountPaid = $payment->amount + $amount;

            $others['total_amount_paid'] = $totalAmountPaid;
            
            Transaction::create([
                'customer_id' => $payment_thread->customer_id,
                'amount' => $payment_thread->amount,
                'transaction_id' => $transaction_id,
                'account_id' => $data['account_id'] ?? $payment_thread->account_id,
                'purpose' => 'balance_contract_payment',
                'payment_slip' => $filename,
                'description' => $payment_thread->description,
                'staff_id' => auth()->user()->staff->id,
                'type' => 'credit',
                'company_id' => $this->sessionDetails('company_id'),
            ]);

            // Update parent payment to reflect all payments
            $payment->update([
                // 'amount' => $payment->amount + $data['amount'],
                'amount' => $totalAmountPaid,
                'balance' => $balance,
            ]);

            if ($balance > 0) {
                $contract_status = 'ongoing';
            } else {
                $contract_status = 'paid';
            }
            
            $others['balance'] = $balance;

            // Generate payment receipt
            $contract->contract_status = $contract_status;
            $contract->save();
            
            $receipt = $this->generateContractPaymentReceipt($payment_thread, $contract, $others);
            
            $payment_thread->update(['receipt_location' => $receipt['filepath'] ?? null]);

            $receipt_location = $receipt['filepath'] ?? null;
            
            \DB::commit();
            
            // Persist file
            return [
                'receipt_location' => $receipt_location ,
                'status' => 'success',
                'payment' => $payment,
                'processed_contract' => $contract,
            ];
            // Do something with the commission from contract here
        } catch (\Throwable $th) {
            //throw $th;
            $res = [
                'status' => 'error',
                'message' => 'An error occurred when making payment for: ' . $contract->contract_name . ' Error: ' . $th->getMessage(),
            ];
            \DB::rollback();
            return $res;
        }

    }

    public function processContractPayment($contract, $data)
    {
        \DB::beginTransaction();

        try {
            $transaction_id = $this->generateTransactionId('CT');

            if (!empty($data['payment_slip'])) {
                $filename = $this->uploadFile($data['payment_slip'], 'payments');
            } else {
                $filename = null;
            }

            $discount = $contract->discount ?? 0;
            $property_price = $contract->property_type_details->estate_price;
            $amount_to_pay = $property_price - $discount;
            $balance = ($property_price - $discount) - $data['amount'];

            // handle when incoming amount is above amount to pay
            if($data['amount'] > $amount_to_pay){
                $res = [
                    'status' => 'error',
                    'message' => 'Amount exceeds amount to pay for: ' . $contract->contract_name,
                ];

                \DB::rollback();
                return $res;
            }

            // Get amount
            if ($balance > 0) {
                $contract_status = 'ongoing';
            } else {
                $contract_status = 'paid';
            }

            // Store Payment
            $balance = ($balance < 0) ? 0 : $balance;
            
            $payment = ContractPayment::updateOrCreate(['customer_id' => $data['customer_id'], 'contract_id' => $contract->id, 'no_of_months' => $data['no_of_months'], 'company_id' => $this->sessionDetails('company_id')], [
                'contract_id' => $contract->id,
                'contract_value' => $property_price,
                'amount' => $data['amount'],
                'balance' => $balance,
                'customer_id' => $data['customer_id'],
                'transaction_id' =>
                $transaction_id,
                'account_id' => $data['account_id'],
                'branch_id' => $data['branch_id'],
                'description' => $data['description'],
                'no_of_months' => $data['no_of_months'],
                'payment_slip' => $filename,
                'property_net_fee' => $property_price - $discount,
                'discount_applied' => $discount,
                'company_id' => $this->sessionDetails('company_id')
            ]);

            // Create Payment thread
            $payment_thread = ContractPaymentThread::updateOrCreate(['contract_payments_id' => $payment->id, 'company_id' => $this->sessionDetails('company_id')], [
                'contract_payments_id' => $payment->id,
                'amount' => $data['amount'],
                'transaction_id' => $payment->transaction_id . 'CPT',
                'payment_slip' => $filename,
                'account_id' => $payment->account_id,
                'branch_id' => $payment->branch_id,
                'description' => $payment->description,
                'customer_id' => $payment->customer_id,
                'company_id' => $this->sessionDetails('company_id')
            ]);

            // Transaction
            Transaction::updateOrCreate(['customer_id' => $payment->customer_id, 'transaction_id' => $payment->transaction_id, 'company_id' => $this->sessionDetails('company_id')], [
                'customer_id' => $payment->customer_id,
                'amount' => $payment->amount,
                'transaction_id' => $payment->transaction_id,
                'account_id' => $payment->account_id,
                'purpose' => 'contract_payment',
                'payment_slip' => $filename,
                'description' => $payment->description,
                'staff_id' => auth()->user()->staff->id,
                'type' => 'credit',
                'company_id' => $this->sessionDetails('company_id'),
            ]);

            $others = [
                'contract_value' => $payment->contract_value,
                'discount_applied' => $payment->discount_applied,
            ];

            $contract->update([
                'contract_payment_transaction_id' => $payment->transaction_id,
                'status' => 'subscriber',
                'contract_status' => $contract_status,
            ]);

            // Generate payment receipt
            $contract->contract_status = $contract_status;
            $receipt = $this->generateContractPaymentReceipt($payment, $contract, $others);
            $payment->update(['receipt_location' => $receipt['filepath'] ?? null]);
            $payment_thread->update(['receipt_location' => $receipt['filepath'] ?? null]);
            
            $receipt_location = $receipt['filepath'] ?? null;
            \DB::commit();

            // Do something with the commission from contract here
            return [
                'receipt_location' => $receipt_location,
                'status' => 'success',
                'payment' => $payment,
                'processed_contract' => $contract,
            ];
        } catch (\Throwable $th) {
            // \Log::info(['File' => 'ContractPaymentController', 'message' => $th->getMessage(), 'Line' => $th->getLine()]);
            $res = [
                'status' => 'error',
                'message' => 'An error occurred when making payment for: ' . $contract->contract_name . ' Error: ' . $th->getMessage(),
            ];
            \DB::rollback();
            return $res;
        }
    }

    public function generateContractPaymentReceipt($payment, $contract,$others)
    {
        $name = $transaction_id ?? $payment->transaction_id;
        $data['receipt_no'] = $name;
        $pathToSave = $this->getPathToSaveFile('receipts') . '/' . $name . '.pdf';

        $pdf = PDF::loadView('receipts.contract-payment', compact('payment', 'data', 'contract','others'))->setPaper('a4', 'portrait');
        
        file_put_contents(base_path() . '/' . config('app.upload_path') . '/' . $pathToSave, $pdf->output());
        // Rmember to create a public folder in the root directory if there is a PDF error

        return [
            'receipt_no' => $name . '.pdf',
            'filename' => $name . '.pdf',
            'filepath' => config('app.upload_path') . '/' . $pathToSave
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(ContractPayment $contractPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContractPayment $contractPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContractPayment $contractPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContractPayment $contractPayment)
    {
        //
    }

    public function seEscavationSetting($contract)
    {
        $contract = CustomerContract::where('id', $contract)->first();
        return response()->json(['message' => 'Success', 'data' => $contract->property_type_details ?? []], 200);
    }

    public function makeSettingAndEscavationPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_id' => 'required|exists:chart_of_accounts,id,company_id,' . $this->sessionDetails('company_id'),
            'contract_id' => 'required|exists:customer_contracts,id,company_id,' . $this->sessionDetails('company_id'),
            'amount' => 'required|numeric|gt:0',
            'type' => 'required|in:SE,INFRASTRUCTURE',
            'payment_slip' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }
       
        try {
            \DB::beginTransaction();
            // Get contract
            $contract = CustomerContract::where('id', $request->contract_id)->first();
            
            if ($request->type == 'SE') {
                $fee = $contract->property_type_details->settings_and_excavation;
                $purpose = 'setting_and_escavation';
            } else {
                $fee = $contract->property_type_details->infrastructural_fee;
                $purpose = 'infrastructural_fee';
            }
            
            $old_payment = SeInfrastructure::where('contract_id', $contract->id)->where('customer_id', $contract->customer_id)->where('type', $request->type)->first();
            $infrastructure_fee = $fee;

            $fee = !empty($old_payment) ? $old_payment->infrastructure_fee : $fee;
            
            $old_amount = !empty($old_payment) ? $old_payment->amount : 0;

            $balance = $fee - ($request->amount + $old_amount);
            $current_amount_paid = $request->amount;
            $total_amount_paid = $request->amount + $old_amount;
            
            if ($request->amount > $fee) {
                \DB::rollback();

                return response()->json([
                    'status' => 'error',
                    'error' => 'Cannot pay more than set price',
                    'data' => null,
                ], 412);
            }

            if ($balance < 0) {
                \DB::rollback();

                return response()->json([
                    'status' => 'error',
                    'error' => 'You have already paid: '. $old_amount.' for this contract',
                    'data' => null,
                ], 412);
            }

            $transaction_id = $this->generateTransactionId();

            if ($request->payment_slip) {
                $filename = $this->uploadFile($request->payment_slip, 'payments');
            } else {
                $filename = null;
            }
            
            $payment = SeInfrastructure::updateOrCreate([
                'contract_id' => $contract->id,
                'customer_id' => $contract->customer_id,
                'type' => $request->type,
                'company_id' => $this->sessionDetails('company_id')
            ],[
                'contract_id' => $contract->id,
                'amount' => $total_amount_paid,
                'balance' => $balance,
                'customer_id' => $contract->customer_id,
                'transaction_id' => $transaction_id,
                'type' => $request->type,
                'account_id' => $request->account_id,
                'payment_slip' => $filename,
                'infrastructure_fee' => $infrastructure_fee,
                'company_id' => $this->sessionDetails('company_id')
            ]);

            $payment_thread = SeInfrastructureThread::create([
                'se_infrastructures_id' => $payment->id,
                'amount' => $current_amount_paid,
                'customer_id' => $contract->customer_id,
                'branch_id' => $this->sessionDetails('branch_id'),
                'account_id' => $request->account_id,
                'transaction_id' => $payment->transaction_id,
                'payment_slip' => $filename,
                'company_id' => $this->sessionDetails('company_id')
            ]);

            $payment->current_amount_paid = $current_amount_paid;
            $payment->total_amount_paid = $total_amount_paid;
        
            // Transaction
            Transaction::updateOrCreate(['customer_id' => $payment->customer_id, 'transaction_id' => $payment->transaction_id, 'company_id' => $this->sessionDetails('company_id')], [
                'customer_id' => $contract->customer_id,
                'amount' => $request->amount,
                'transaction_id' => $payment->transaction_id,
                'account_id' => $request->account_id,
                'purpose' => $purpose,
                'payment_slip' => $filename,
                'description' => null,
                'staff_id' => auth()->user()->staff->id,
                'type' => 'credit',
                'company_id' => $this->sessionDetails('company_id'),
            ]);
            
            $contract->update([
                'sei_payment_transaction_id' => $payment->transaction_id,
            ]);
            
            if($request->type == 'SE'){
                $contract->update([
                    'se_status' => $balance > 0 ? 'In Progress' : 'Paid',
                ]);
            }elseif($request->type == 'INFRASTRUCTURE') {
                $contract->update([
                    'infrastructure_status' => $balance > 0 ? 'In Progress' : 'Paid',
                ]);
            }

            if ($payment->balance <= 0) {
                $contract->update([
                    'contract_status' => 'closed',
                ]);
            }

            $contract->fee = $payment->infrastructure_fee;
            
            $receipt = generateGenericPaymentReceipt($payment, $contract);
        
            unset($payment->current_amount_paid);
            unset($payment->total_amount_paid);

            $payment->update([
                'receipt_location' => $receipt['filepath'] ?? null
            ]);

            $payment_thread->update([
                'receipt_location' => $receipt['filepath'] ?? null
            ]);
            
            $data['attachments'][] = $receipt['filepath'];

            $data['type'] = 'se.escavation.payment';
            $data['element'] = $request->type;
            $data['name'] =  $payment->customer->user->first_name;
            $data['email'] =  $payment->customer->user->email;
            $data['contract_ids'] = [$contract->id];
            
            $this->logEmail($data);
            \DB::commit();
            
            return response()->json(['message' => 'Success', 'data' => []], 200);

        } catch (\Throwable $th) {
            \Log::info(['SE/Infrastructure Error: ' => $th->getMessage(). ' File: '. $th->getFile(). ' Line:'. $th->getLine()]);
            \DB::rollback();
            return response()->json(['message' => 'Success', 'data' => []], 200);
        }
    }
}
