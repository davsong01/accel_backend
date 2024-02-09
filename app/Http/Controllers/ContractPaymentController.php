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
            'payment_history' => $paymentHistory->payment_thread,
        ];

        return response()->json(['message' => 'Succee', 'data' => $history], 200);
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

        foreach ($contract_ids as $contract) {
            $contract_validator = Validator::make($contract, [
                'allow_installment' => 'required|In:0,1',
                'no_of_months' => 'nullable',
                'amount' => 'required',
                'contract_id' => 'required|exists:customer_contracts,id,form_approval_status,approved,company_id,' . $this->sessionDetails('company_id'), 'customer_id', $request->customer_id,
            ]);

            if ($contract_validator->fails()) {
                return response()->json([
                    'error' => $contract_validator->messages()->first(),
                    'data' => null,
                ], 412);
            }

            $contractX = CustomerContract::where('id', $contract['contract_id'])->where('customer_id', $request->customer_id)->where('company_id', $this->sessionDetails('company_id'))->first();

            $contract = array_merge($contract, $request->all());

            $res = $this->processContractPayment($contractX, $contract);

            if (isset($res['status']) && $res['status'] == 'error') {
                return response()->json(['error' => $res['message'], 'data' => []], 200);
            }

            $payments[] = [
                'contract_id' => $contractX->id,
                'contract_name' => $contractX->contract_name,
                'contract_value' => $res->contract_value,
                'discount_applied' => $res->discount_applied,
                'property_net_fee' => $res->property_net_fee,
                'transaction_id' => $res->transaction_id,
                'amount' => $res->amount,
                'description' => $res->description,
                'balance' => $res->balance,
                'no_of_months' => $res->no_of_months,
                'payment_slip' => $res->filename,
                'receipt_location' => $res->receipt_location ?? null,
            ];
        }

        // Log Email of receipt and provisional letter
        $data['type'] = 'contract.payment';
        $data['name'] =  $contractX->customer->user->first_name;
        $data['email'] =  $contractX->customer->user->email;
        $data['attachments'] = array_column($payments, 'receipt_location');

        $this->logEmail($data);

        LogService::admin(auth()->user()->id, NULL, 'Update a company\'s contract, contract payment, contract payment thread and transaction (' . $contractX->id . ')', 'UPDATE');
        return response()->json(['message' => 'Payment successful', 'data' => []], 200);
    }

    public function continueContractPayment(Request $request, $contract_id)
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

            $contract = CustomerContract::where('id', $contract_id['contract_id'])->first();
            $payment = ContractPayment::where('contract_id', $contract_id['contract_id'])->first();

            if ($payment->balance == 0) {
                return response()->json([
                    'error' => 'Full payment has already been made for : ' . $contract->contract_name,
                    'data' => null,
                ], 422);
            }
            $request['amount'] = $contract_id['amount'];

            $res = $this->processContinueContractPayment($contract, $request->all(), $payment);
    
            if (isset($res['status']) && $res['status'] == 'error') {
                return response()->json(['error' => $res['message'], 'data' => []], 200);
            }

            $payments[] = [
                'contract_id' => $contract->id,
                'contract_name' => $contract->contract_name,
                'contract_value' => $res->contract_value,
                'discount_applied' => $res->discount_applied,
                'property_net_fee' => $res->property_net_fee,
                'transaction_id' => $res->transaction_id,
                'amount' => $res->amount,
                'description' => $res->description,
                'balance' => $res->balance,
                'no_of_months' => $res->no_of_months,
                'payment_slip' => $res->filename,
                'receipt_location' => $res->receipt_location ?? null,
            ];
    
            // Log email
            LogService::admin(auth()->user()->id, NULL, 'Update a company\'s contract, contract payment, contract payment thread and transaction (' . $contract->id . ')', 'UPDATE');
            
        }
        
        $this->logContractEmail($contract, $payments);
        return response()->json(['message' => 'Payment successful', 'data' => []], 200);
    }

    public function logContractEmail($contract, $payments)
    {
        // Log Email of receipt and provisional letter
        if ($contract->contract_status == 'ongoing') {
            $data['type'] = 'contract.payment';
        } else {
            $data['type'] = 'contract.payment';
        }
        $data['name'] =  $contract->customer->user->first_name;
        $data['email'] =  $contract->customer->user->email;
        $data['attachments'] = array_column($payments, 'receipt_location');;

        $this->logEmail($data);
    }

    public function processContinueContractPayment($contract, $data, $payment)
    {
        // try {
        $transaction_id = $this->generateTransactionId('CTB');

        if (!empty($data['payment_slip'])) {
            $filename = $this->uploadFile($data['payment_slip'], 'payments');
        } else {
            $filename = null;
        }

        $amount = $data['amount'];
        $fee = $payment->property_net_fee;
        $balance = $payment->balance;
        $newbalance = $fee - ($payment->amount + $data['amount']);

        if ($amount > $balance) {
            $res = [
                'status' => 'error',
                'message' => 'You cannot make payment of more than: ' . $balance . ' for this contract',
            ];

            return $res;
        }

        $newbalance = $balance - $amount;

        // Create Payment thread
        $payment_thread = ContractPaymentThread::create([
            'contract_payments_id' => $payment->id,
            'amount' => $data['amount'],
            'transaction_id' => $transaction_id,
            'payment_slip' => $filename,
            'account_id' => $data['account_id'],
            'branch_id' => $payment->branch_id,
            'description' => $data['description'],
            'customer_id' => $data['customer_id'],
            'company_id' => $this->sessionDetails('company_id')
        ]);

        $transaction = Transaction::create([
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

        $payment->update([
            'amount' => $payment->amount + $data['amount'],
            'balance' => $newbalance,
        ]);

        $others['balance'] = $newbalance;
        $others['contract_value'] = $payment->contract_value;
        $others['discount_applied'] = $payment->discount_applied;
        $others['total_amount_paid'] = $fee - $newbalance;
        
        // Generate payment receipt
        $receipt = $this->generateContractPaymentReceipt($payment_thread, $contract, $others);
        $payment_thread->update(['receipt_location' => $receipt['filepath'] ?? null]);
        
        if ($newbalance <= 0) {
            $contract->update([
                'contract_status' => 'paid',
            ]);
        }

        return $payment;

        // Do something with the commission from contract here
        // } catch (\Throwable $th) {
        //     //throw $th;
        //     dd($th->getMessage());
        //     $res = [
        //         'status' => 'error',
        //         'message' => 'An error occurred when making payment for: ' . $contract->contract_name . ' Error: ' . $th->getMessage(),
        //     ];

        //     return $res;
        // }

    }

    public function processContractPayment($contract, $data)
    {
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

            // Get amount
            if ($data['allow_installment'] == 1) {
                // $no_of_months = $data['no_of_months'] ?? 1;
                // $caliberation = $amount_to_pay / $no_of_months;
                // $amount_to_pay = $caliberation;
                $contract_status = 'ongoing';
                // dd($caliberation, $no_of_months, $data['amount']);
                // if ($data['amount'] < $caliberation) {
                //     $res = [
                //         'status' => 'error',
                //         'message' => 'Amount must be equal to or greater than the first instalmental payment of ' . number_format($caliberation) . ' for '.$contract->contract_name,
                //     ];

                //     return $res;
                // }
            } else {
                $contract_status = 'paid';
            }

            // if ($amount_to_pay != $data['amount']) {
            //     $res = [
            //         'status' => 'error',
            //         'message' => 'Please check Net Property Fee for ' . $contract->contract_name . ' It should be exactly: ' . $amount_to_pay,
            //     ];

            //     return $res;
            // }

            $balance = ($property_price - $discount) - $data['amount'];

            // Store Payment
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

            // Generate payment receipt

            if (env('ENT') == 'local') {
                $receipt = $this->generateContractPaymentReceipt($payment, $contract, $others);
                $payment->update(['receipt_location' => $receipt['filepath'] ?? null]);
                $payment_thread->update(['receipt_location' => $receipt['filepath'] ?? null]);
            }

            $contract->update([
                'contract_payment_transaction_id' => $payment->transaction_id,
                'status' => 'subscriber',
                'contract_status' => $contract_status,
            ]);

            return $payment;

            // Do something with the commission from contract here
        } catch (\Throwable $th) {
            // \Log::info(['File' => 'ContractPaymentController', 'message' => $th->getMessage(), 'Line' => $th->getLine()]);
            $res = [
                'status' => 'error',
                'message' => 'An error occurred when making payment for: ' . $contract->contract_name . ' Error: ' . $th->getMessage(),
            ];
            
            return $res;
        }
    }

    public function generateContractPaymentReceipt($payment, $contract,$others)
    {
        $name = $transaction_id ?? $payment->transaction_id;
        $data['receipt_no'] = $name;
        $pathToSave = $this->getPathToSaveFile('receipts') . '/' . $name . '.pdf';
        // dd($payment->customer, $payment->transaction);
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

    public function seEscavationSetting(CustomerContract $contract)
    {
        return response()->json(['message' => 'Success', 'data' => $contract->property_type_details], 200);
    }

    public function makeSettingAndEscavationPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_id' => 'required|exists:chart_of_accounts,id,company_id,' . $this->sessionDetails('company_id'),
            'contract_id' => 'required|exists:customer_contracts,id,company_id,' . $this->sessionDetails('company_id'),
            'amount' => 'required',
            'type' => 'required|in:SE,INFRASTRUTURE',
            'payment_slip' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }


        // Get contract
        $contract = CustomerContract::where('id', $request->contract_id)->first();

        if ($contract->contract_status != 'completed') {
            return response()->json([
                'error' => 'Please check that the status of this contract is: completed!',
                'data' => null,
            ], 412);
        }

        if ($request->type == 'SE') {
            $fee = $contract->property_type_details->settings_and_excavation;
            $purpose = 'setting_and_escavation';
        } else {
            $fee = $contract->property_type_details->infrastructural_fee;
            $purpose = 'infrastructural_fee';
        }

        if ($request->amount > $fee) {
            return response()->json([
                'error' => 'Cannot pay more than set price',
                'data' => null,
            ], 412);
        }

        $balance = $fee - $request->amount;
        $transaction_id = $this->generateTransactionId();
        if ($request->payment_slip) {
            $filename = $this->uploadFile($request->payment_slip, 'payments');
        } else {
            $filename = null;
        }

        $payment = SeInfrastructure::updateOrCreate(['contract_id' => $request->contract_id, 'amount' => $request->amount, 'company_id' => $this->sessionDetails('company_id')], [
            'contract_id' => $contract->id,
            'amount' => $request->amount,
            'balance' => $balance,
            'customer_id' => $contract->customer_id,
            'transaction_id' => $transaction_id,
            'type' => $request->type,
            'account_id' => $request->account_id,
            'payment_slip' => $filename,
            'infrastructure_fee' => $fee,
            'company_id' => $this->sessionDetails('company_id')
        ]);

        // Create Payment thread
        $payment_thread = SeInfrastructureThread::updateOrCreate(['se_infrastructures_id' => $payment->id, 'company_id' => $this->sessionDetails('company_id')], [
            'se_infrastructures_id' => $payment->id,
            'amount' => $request->amount,
            'customer_id' => $contract->customer_id,
            'branch_id' => $this->sessionDetails('branch_id'),
            'account_id' => $request->account_id,
            'transaction_id' => $payment->transaction_id,
            'payment_slip' => $filename,
            'company_id' => $this->sessionDetails('company_id')
        ]);

        // Transaction
        Transaction::updateOrCreate(['customer_id' => $payment->customer_id, 'transaction_id' => $payment->transaction_id, 'company_id' => $this->sessionDetails('company_id')], [
            'customer_id' => $payment->customer_id,
            'amount' => $request->amount,
            'transaction_id' => $payment->transaction_id,
            'account_id' => $payment->account_id,
            'purpose' => $purpose,
            'payment_slip' => $filename,
            'description' => null,
            'staff_id' => auth()->user()->staff->id,
            'type' => 'credit',
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        $contract->update([
            'contract_status' => 'closed',
            'sei_payment_transaction_id' => $payment->transaction_id
        ]);

        // Generate payment receipt, stop here todat
        $contract->fee = $payment->infrastructure_fee;
        if(env('ENT' == 'local')){
            $receipt = generateGenericPaymentReceipt($payment, $contract);
    
            $payment->update(['receipt_location' => $receipt['filepath'] ?? null]);
            $payment_thread->update(['receipt_location' => $receipt['filepath'] ?? null]);
    
            $data['attachments'] = $receipt['filepath'];

        }

        $data['type'] = 'se.escavation.payment';
        $data['element'] = $request->type;
        $data['name'] =  $payment->customer->user->first_name;
        $data['email'] =  $payment->customer->user->email;

        $this->logEmail($data);

        return response()->json(['message' => 'Success', 'data' => []], 200);
    }
}
