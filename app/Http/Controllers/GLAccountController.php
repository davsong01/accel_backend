<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\User;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GLAccountController extends Controller
{

    public function __construct(
        private readonly TransactionService $transactionService,
    )
    {
    }

    /**
     * Display a listing of the GL Account.
     */
    public function index(): JsonResponse
    {
        $company_id = $this->sessionDetails('company_id');

        $accounts = ChartOfAccount::with(['account_category', 'account_type'])->where(['status' => 'active', 'company_id' => $company_id])->latest()->paginate(20);
        return response()->json(['message' => 'Success', 'data' => $accounts], 200);
    }

    /**
     * Display a listing of all the transactions on GL Account.
     *
     * @param Request $request
     * @param ChartOfAccount $account
     * @return JsonResponse
     */
    public function transactions(Request $request, ChartOfAccount $account): JsonResponse
    {
        $company_id = $this->sessionDetails('company_id');
        $transactions = $this->transactionService
            ->fetchAccountTransactionQuery(
                account: $account,
                companyId: $company_id,
                request: $request
            )
            ->orderBy('created_at', 'desc')
            ->paginate($request->has('per_page') ? $request->input('per_page') : 10)
            ->withQueryString()
            ->through(fn($item) => $this->transactionService->resolveTransactionInfo($item));

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
    public function search(Request $request)
    {
        $params = $request->only(['status', 'accounttype_id']);
        $company_id = $this->sessionDetails('company_id');

        $query = ChartOfAccount::with(['account_category', 'account_type'])->where('company_id', $company_id)->where($params);

        if ($account_number = $request->input('account_number'))
            $query->where('account_number', 'LIKE', '%' . $account_number . '%');

        if ($name = $request->input('name'))
            $query->where('name', 'LIKE', '%' . $name . '%');

        $accounts = $query->latest()->paginate(20);
        return response()->json(['message' => 'Success', 'data' => $accounts], 200);
    }
}
