<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\ContractPayment;
use App\Models\CustomerContract;
use App\Models\ContractPaymentThread;
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

    // public function makeFormPayment(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'contract_ids' => 'required',
    //         'account_id' => 'required|exists:chart_of_accounts,id,company_id,' . $this->sessionDetails('company_id'),
    //         'payment_slip' => 'nullable',
    //         'description' => 'nullable',
    //         'amount' => 'required',
    //         'customer_id' => 'required',
    //         'transaction_date' => 'required'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'error' => $validator->messages()->first(),
    //             'data' => null,
    //         ], 412);
    //     }

    //     $data = $validator->valid();

    //     $request['contract_ids'] = json_decode($request['contract_ids'], true);
    //     $contract_ids = array_column($request['contract_ids'], 'contract_id');

    //     // Sanitize customer contract based on estate id
    //     $datum = $request['contract_ids'];

    //     $sanitized = [];
    //     foreach ($datum as $data) {
    //         $estate_validator = Validator::make($data, [
    //             'estate_id' => 'required|exists:estates,id,company_id,' . $this->sessionDetails('company_id'),
    //         ]);

    //         if ($estate_validator->fails()) {
    //             return response()->json([
    //                 'error' => $estate_validator->messages()->first(),
    //                 'data' => null,
    //             ], 412);
    //         }

    //         $estates = $estate_validator->valid();

    //         $newContracts = $this->sanitizeContractsByEstateIds($estates, $request->customer_id);

    //         if (!empty($newContracts)) {
    //             $sanitized[] = [
    //                 'estate_id' => $data['estate_id'],
    //                 'contract_ids' => $newContracts
    //             ];
    //         }
    //     }

    //     // try to get form payment for each estate
    //     $total_form_amount = 0;

    //     foreach ($sanitized as $key => &$sa) {
    //         $form_amount = $this->generateFormPayment($sa['estate_id']);
    //         $total_form_amount = $total_form_amount + $form_amount;
    //         $sa['form_amount'] = $form_amount;
    //     }

    //     if ($total_form_amount < 1) {
    //         return response()->json([
    //             'error' => 'Error calculating form amount, Please ensure form amount is set for selected estates!',
    //             'data' => null,
    //         ], 412);
    //     }

    //     if ($request->amount != $total_form_amount) {
    //         return response()->json([
    //             'error' => 'Amount does not correspond with selected contract. Amount should be exactly ' . $total_form_amount,
    //             'data' => null,
    //         ], 412);
    //     }

    //     if (!empty($request->payment_slip)) {
    //         $filename = $this->uploadFile($request->payment_slip, 'payments');
    //     }

    //     $customer = Customer::where('id', $request->customer_id)->first();

    //     // process receipt for contracts according to estates
    //     foreach ($sanitized as $key => $contract) {
    //         $transaction_id = $this->generateTransactionId();
    //         $customer_contract_ids = implode(',', $contract['contract_ids']);

    //         $payment = Transaction::updateOrCreate(['estate_id' => $contract['estate_id'], 'customer_id' => $customer->id], [
    //             // 'customer_contract_ids' => $customer_contract_ids,
    //             'account_id' => $request->account_id,
    //             'purpose' => 'form_payment',
    //             'payment_slip' => $filename ?? null,
    //             'description' => $request->description,
    //             'amount' => $contract['form_amount'],
    //             'staff_id' => auth()->user()->staff->id,
    //             'type' => 'credit',
    //             'estate_id' => $contract['estate_id'],
    //             'customer_id' => $customer->id,
    //             'transaction_id' => $transaction_id,
    //             'company_id' => $this->sessionDetails('company_id'),
    //         ]);

    //         if (env('ENT') !== 'local') {
    //             $receipt = $this->generateFormPaymentReceipt($payment);
    //             $payment->update(['receipt_location' => $receipt['filepath'] ?? null]);
    //             $receipts[] = $receipt['filepath'] ?? null;
    //         }

    //         // Move contract to prospect
    //         foreach ($contract['contract_ids'] as $ids) {
    //             CustomerContract::where('id', $ids)->update([
    //                 'status' => 'prospect',
    //                 'form_payment_transaction_id' => $transaction_id,
    //                 'transaction_date' => Carbon::parse($request->transaction_date)
    //             ]);
    //         }
    //     }

    //     $customer->form_status = 'Not Filled';
    //     $customer->save();

    //     // Create customer response form with selected data
    //     CustomerForm::updateOrCreate(
    //         ['customer_id' => $customer->id, 'company_id' => $this->sessionDetails('company_id')],
    //         ['bypass_approval' => null]
    //     );

    //     // Log email
    //     $data['type'] = 'form.payment';
    //     $data['name'] =  $customer->user->first_name ?? null;
    //     $data['email'] =  $customer->user->email ?? null;
    //     $data['attachments'] = $receipts ?? null;

    //     $this->logEmail($data);

    //     return response()->json(['message' => 'Success', 'data' => null], 200);
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
        
        if(count($contract_ids) < 1){
            return response()->json([
                'error' => 'Please select one or more contracts to pay for',
                'data' => null,
            ], 412);
        }
        
        $payments = [];

        foreach($contract_ids as $contract){
            $contract_validator = Validator::make($contract, [
                'allow_installment' => 'required|In:0,1',
                'no_of_months' => 'nullable',
                'amount' => 'required',
                'contract_id' => 'required|exists:customer_contracts,id,form_approval_status,approved,company_id,' . $this->sessionDetails('company_id'),'customer_id',$request->customer_id,
            ]);

            if ($contract_validator->fails()) {
                return response()->json([
                    'error' => $contract_validator->messages()->first(),
                    'data' => null,
                ], 412);
            }

            $contractX = CustomerContract::where('id',$contract['contract_id'])->where('customer_id', $request->customer_id)->where('company_id', $this->sessionDetails('company_id'))->first();

            $contract = array_merge($contract, $request->all());
            
            $res = $this->processContractPayment($contractX, $contract);
           
            if(isset($res['status']) && $res['status'] == 'error'){
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
            ];
            
        }


        // Log Email

        return response()->json(['message' => 'Payment successful', 'data' => []], 200);
    
    }
    
    public function processContractPayment($contract, $data){
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
            \Log::info([$discount, $amount_to_pay]);
           
            // Get amount
            if ($data['allow_installment'] == 1) {
                $no_of_months = $data['no_of_months'] ?? 1;
                $caliberation = $amount_to_pay / $no_of_months;
                $amount_to_pay = $caliberation;
                $contract_status = 'ongoing';
                // dd($caliberation, $no_of_months, $data['amount']);
                if ($data['amount'] < $caliberation) {
                    $res = [
                        'status' => 'error',
                        'message' => 'Amount must be equal to or greater than the first instalmental payment of ' . number_format($caliberation) . ' for '.$contract->contract_name,
                    ];

                    return $res;
                }
            }else{
                $contract_status = 'completed';
            }
            
            if ($amount_to_pay != $data['amount']) {
                $res = [
                    'status' => 'error',
                    'message' => 'Please check Net Property Fee for ' . $contract->contract_name . ' It should be exactly: ' . $amount_to_pay,
                ];

                return $res;
            }

            $balance = ($property_price - $discount) - $data['amount'];
           
            // Store Payment
            $payment = ContractPayment::updateOrCreate(['customer_id' => $data['customer_id'], 'contract_id' => $contract->id, 'no_of_months' => $data['no_of_months'], 'company_id' => $this->sessionDetails('company_id')],[
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
            ContractPaymentThread::updateOrCreate(['contract_payments_id' => $payment->id, 'company_id' => $this->sessionDetails('company_id')], [
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

            // Generate payment receipt
            $contract->update([
                'contract_payment_transaction_id' => $payment->transaction_id,
                'status' => $contract_status
            ]);

            return $payment;
            
            // Do something with the commission from contract here
        } catch (\Throwable $th) {
            //throw $th;
            \Log::error($th);
            dd($th->getMessage().$th->getLine());
            $res = [
                'status' => 'error',
                'message' => 'An error occurred when making payment for: '.$contract->contract_name,
            ];
        }

        return $res;
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
}
