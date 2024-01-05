<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FinanceController extends Controller
{
    /**
     * Display a listing of the GL Account.
     */
    public function GLAccount(): JsonResponse
    {
        $accounts = ChartOfAccount::with(['account_category', 'account_type'])->paginate(20);
        return response()->json(['message' => 'Success', 'data' => $accounts], 200);
    }

    /**
     * Display a listing of all the transactions on GL Account.
     */
    public function GLAccountTransactions(ChartOfAccount $account): JsonResponse
    {
        $transactions = $account->payments()->paginate(20);
        return response()->json(['message' => 'Success', 'data' => $transactions], 200);
    }

     /**
     * Display a listing of the GL Ledger.
     * 
     */
    public function GLLedger(): JsonResponse
    {
        $transactions = Transaction::with(['account', 'staff:id,user_id', 'staff.user:id,status,type,first_name,middle_name,last_name,phone_number', 'customer:id'])->latest()->paginate(20);
        return response()->json(['message' => 'success', 'data' => $transactions], 200);
    }

     /**
     * Create a resource for GL Ledger.
     * 
     */
    public function createGLLedger(Request $request): JsonResponse
    {
        $company_id = $this->sessionDetails('company_id');

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:CREDIT,DEBIT',
            'transaction_type' => 'required|string',
            'purpose' => 'required|string',
            'customer_id' => 'exists:customers,id,company_id,' . $company_id,
            'estate_id' => 'required|exists:estates,id,company_id,' . $company_id,
            'customer_contract_ids' => 'array',
            'customer_contract_ids.*' => 'exists:customer_contracts,id,customer_id,' . $request->customer_id,
            'account_id' => 'required|exists:chart_of_accounts,id',
            'amount' => 'required|numeric',
            'created_at' => 'date|before:tomorrow',
            'payment_slip' => 'file',
            'description' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $attributes = $validator->validated();

        $transaction = $this->createTransaction($attributes);
        return response()->json(['message' => 'success', 'data' => $transaction], 200);
    }

    /**
     * Search/filter GL Ledger
     * 
     */
     public function searchGLLedger(Request $request){ 
        $params = $request->only((new Transaction)->getAttributes());
        $query = Transaction::with(['account', 'staff:id,user_id', 'staff.user:id,first_name,middle_name,last_name', 'customer:id,user_id', 'customer.user:id,status,type,first_name,middle_name,last_name'])->where($params);

        $from = $request->input('from');
        $to = $request->input('to');
        if($from && $to)
            $query->whereBetween('created_at', [$from, $to]);

        if($request->input('staff_name') && $name = $request->input('staff_name')){
            $user = User::where('first_name', 'LIKE', '%'.$name.'%')
            ->orWhere('last_name', 'LIKE', '%'.$name.'%')
            ->orWhere('middle_name', 'LIKE', '%'.$name.'%')->first();
            $query->where('staff_id', $user?->id);
        }

        if($name = $request->input('account_number')){
            $account = ChartOfAccount::where('account_number', $name)->first();
            $query->where('account_id', $account?->id);
        }

        $transactions = $query->latest()->paginate(20);
        return response()->json(['message' => 'success', 'data' => $transactions], 200);
     }

}
