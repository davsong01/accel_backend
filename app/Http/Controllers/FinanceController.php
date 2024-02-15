<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\User;
use App\Services\LogService;
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
        $company_id = $this->sessionDetails('company_id');

        $accounts = ChartOfAccount::with(['account_category', 'account_type'])->where(['status' => 'active', 'company_id' => $company_id])->latest()->paginate(20);
        return response()->json(['message' => 'Success', 'data' => $accounts], 200);
    }

    /**
     * Display a listing of all the transactions on GL Account.
     */
    public function GLAccountTransactions(ChartOfAccount $account): JsonResponse
    {
        $transactions = $account->payments()->with(['staff:id,user_id,account_name,account_number'])->latest()->paginate(20);
        return response()->json([
            'message' => 'Success',
            'data' => [
                'account' => $account->load(['account_category', 'account_type']),
                'transactions' => $transactions
            ]
        ], 200);
    }


    /**
     * Search/filter GL Account
     * 
     */
    public function searchGLAccount(Request $request)
    {
        $params = $request->only(['status', 'accounttype_id']);
        $company_id = $this->sessionDetails('company_id');


        $query = ChartOfAccount::with(['account_category', 'account_type'])->where('company_id', $company_id)->where($params);

        if ($account_number = $request->input('account_number'))
            $query->where('account_number', 'LIKE', '%' . $account_number . '%');

        if ($name = $request->input('name'))
            $query->where('name', 'LIKE', '%' . $name . '%');

        $accounts = $query->latest()->paginate(20);
        return response()->json(['message' => 'success', 'data' => $accounts], 200);
    }


    /**
     * Display a listing of the GL Ledger.
     * 
     */
    public function GLLedger(): JsonResponse
    {
        $company_id = $this->sessionDetails('company_id');

        $transactions = Transaction::with(['account', 'staff:id,user_id', 'staff.user:id,status,type,first_name,middle_name,last_name,phone_number', 'customer:id'])
        ->where('company_id', $company_id)
        ->latest()
        ->paginate(20);
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
            'transaction_type_id' => 'required|exists:transaction_types,id',
            'transaction_purpose_id' => 'required|exists:transaction_purposes,id,transaction_type_id,' . $request->transaction_type_id,
            'customer_id' => 'exists:customers,id,company_id,' . $company_id,
            'estate_id' => 'exists:estates,id,company_id,' . $company_id,
            'customer_contract_id' => 'exists:customer_contracts,id,customer_id,' . $request->customer_id,
            'account_id' => 'required|exists:chart_of_accounts,id',
            'amount' => 'required|numeric',
            'created_at' => 'date|before:tomorrow',
            'payment_slip' => 'file|mimetypes:image/*,application/pdf',
            'description' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $attributes = [...$validator->validated(), 'company_id' => $company_id];

        $transaction = $this->createTransaction($attributes);

        $customer = Customer::find($request->customer_id);
        LogService::admin(auth()->user()->id, $customer?->user?->id, 'New transaction entry (' . $transaction->id . ')', 'CREATE');
        return response()->json(['message' => 'success', 'data' => $transaction], 200);
    }

    /**
     * Search/filter GL Ledger
     * 
     */
    public function searchGLLedger(Request $request)
    {
        $params = $request->only(['type', 'account_id']);
        $company_id = $this->sessionDetails('company_id');

        $query = Transaction::with(['account', 'staff:id,user_id', 'staff.user:id,first_name,middle_name,last_name', 'customer:id,user_id', 'customer.user:id,status,type,first_name,middle_name,last_name'])->where('company_id', $company_id)->where($params);

        $from = $request->input('from');
        $to = $request->input('to');
        if ($from && $to)
            $query->whereBetween('created_at', [$from, $to]);

        if ($request->input('staff_name') && $name = $request->input('staff_name')) {
            $users = User::with(['staff'])->where('company_id', $company_id)->whereNotNull('company_id')
                ->where('first_name', 'LIKE', '%' . $name . '%')
                ->orWhere('last_name', 'LIKE', '%' . $name . '%')
                ->orWhere('middle_name', 'LIKE', '%' . $name . '%')->get();
            $staff_ids = $users->whereNotNull('staff.id')->pluck('staff.id')->toArray();

            $query->whereIn('staff_id', $staff_ids);
        }

        $transactions = $query->latest()->paginate(20);
        return response()->json(['message' => 'success', 'data' => $transactions], 200);
    }

    /**
     * Search/filter GL Ledger
     * 
     */
    public function showGLLedger(Transaction $transaction)
    {

        $transaction->load(['account', 'staff:id,user_id', 'staff.user:id,first_name,middle_name,last_name', 'customer:id,user_id', 'customer.user:id,status,type,first_name,middle_name,last_name']);

        return response()->json(['message' => 'success', 'data' => $transaction], 200);
    }
}
