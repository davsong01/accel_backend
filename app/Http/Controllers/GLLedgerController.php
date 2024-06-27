<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaction;
use App\Models\User;
use App\Services\LogService;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GLLedgerController extends Controller
{
    public function __construct(
        private readonly TransactionService $transactionService,
    )
    {
    }

     /**
     * Display a listing of the GL Ledger.
     *
     */
    public function index(Request $request): JsonResponse
    {
        $company_id = $this->sessionDetails('company_id');

        $transactions = $this->transactionService
            ->fetchGLAccountTransactionQuery(
                companyId: $company_id,
                request: $request
            )
            ->orderBy('created_at', 'desc')
            ->paginate($request->has('per_page') ? $request->input('per_page') : 20)
            ->withQueryString();

        return response()->json(['message' => 'Success', 'data' => $transactions], 200);
    }

    /**
     * Create a resource for GL Ledger.
     *
     */
    public function create(Request $request): JsonResponse
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
            'payment_mode_id' => 'required|exists:payment_modes,id',
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
        return response()->json(['message' => 'Success', 'data' => $transaction], 200);
    }

    /**
     * Search/filter GL Ledger
     *
     */
    public function search(Request $request): JsonResponse
    {
        $params = $request->only(['type', 'account_id']);
        $company_id = $this->sessionDetails('company_id');

        $transactions = $this->transactionService
            ->fetchGLAccountTransactionQuery(
                companyId: $company_id,
                request: $request
            )
            ->where($params)
            ->orderBy('created_at', 'desc')
            ->paginate($request->has('per_page') ? $request->input('per_page') : 20)
            ->withQueryString();

        return response()->json(['message' => 'Success', 'data' => $transactions], 200);
    }

    /**
     * Search/filter GL Ledger
     *
     */
    public function show(Transaction $transaction)
    {

        $transaction
            ->load([
                'account',
                'company:id,name',
                'staff:id,user_id,branch_id',
                'staff.user:id,first_name,middle_name,last_name',
                'staff.branch:id,name',
                'customer:id,user_id',
                'customer.user:id,status,type,first_name,middle_name,last_name',
                'txn_type:id,name',
                'txn_purpose:id,name',
                'payment_mode:id,mode as name'
            ]);

        return response()->json(['message' => 'Success', 'data' => $transaction], 200);
    }
}
