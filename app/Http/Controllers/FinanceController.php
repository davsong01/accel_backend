<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}
