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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'allow_installment' => 'required|In:0,1',
            'no_of_months' => 'nullable',
            'amount' => 'required',
            'balance' => 'nullable',
            'account_id' => 'required|exists:chart_of_accounts,id,company_id,' . $this->sessionDetails('company_id'),
            'customer_id' => 'required|exists:customers,id,company_id,' . $this->sessionDetails('company_id'),
            'contract_id' => 'required|exists:customer_contracts,id,company_id,' . $this->sessionDetails('company_id'),
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

        $transaction_id = $this->generateTransactionId();
        
        if (!empty($request->payment_slip)) {
            $filename = $this->uploadFile($request->payment_slip, 'payments');
        }else{
            $filename = null;
        }

        $contract = CustomerContract::with(['estate'])->where('id', $request->contract_id)->first();
        $balance = $contract->property_type_details->estate_price - $request->amount;
        $contract_value = $contract->property_type_details->estate_price;
        
        // Get amount
        if(!empty($request->allow_installment)){
            if($request->allow_installment == 1){
                $no_of_months = $request->no_of_months ?? 1;
                $caliberation = $contract_value / $request->no_of_months;

                if($request->amount < $caliberation){
                    return response()->json([
                        'error' => 'Amount must be equal to or greater than the first instalment payment of '.number_format($caliberation),
                        'data' => null,
                    ], 412);
                }
             
            }
        }
        
        // Store Payment
        $payment = ContractPayment::updateOrCreate([
            'contract_value' => $contract_value,
            'amount' => $request->amount,
            'balance' => $balance,
            'customer_id' => $request->customer_id,
            'transaction_id' => $transaction_id,
            'account_id' => $request->account_id,
            'branch_id' => $request->branch_id,
            'description' => $request->description,
            'no_of_months' => $request->no_of_months,
            'payment_slip' => $filename,
            'company_id' => $this->sessionDetails('company_id')
        ]);
        
        // Create Payment thread
        ContractPaymentThread::updateOrCreate(['contract_payments_id' => $payment->id],[
            'contract_payments_id' => $payment->id,
            'amount' => $request->amount,
            'transaction_id' => $transaction_id.'CPT',
            'payment_slip' => $filename,
            'account_id' => $request->account_id,
            'branch_id' => $request->branch_id,
            'description' => $request->description,
            'customer_id' => $request->customer_id,
            'company_id' => $this->sessionDetails('company_id')
        ]);

        // Transaction
        Transaction::updateOrCreate(['customer_id' => $request->customer_id, 'transaction_id' => $payment->transaction_id], [
            'customer_id' => $request->customer_id,
            'amount' => $request->amount,
            'transaction_id' => $payment->transaction_id,
            'account_id' => $request->account_id,
            'purpose' => 'contract_payment',
            'payment_slip' => $filename ?? null,
            'description' => $request->description,
            'staff_id' => auth()->user()->staff->id,
            'type' => 'credit',
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        // Generate payment receipt
        $contract->update([
            'no_of_months' => $request->no_of_months ?? 1,
            'allow_installment' => $request->allow_installment ?? 0
        ]);


        return response()->json(['message' => 'Payment successful', 'data' => null], 200);

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
