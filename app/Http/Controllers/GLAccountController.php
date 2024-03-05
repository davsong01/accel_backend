<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GLAccountController extends Controller
{
    
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
     */
    public function transactions(Request $request, ChartOfAccount $account): JsonResponse
    {
        $query = $account->payments();

        $from = $request->input('from');
        $to = $request->input('to');
        if ($from && $to) {
            $from = Carbon::parse($request->input('from'));

            $to = Carbon::parse($request->input('to'));
            if ($to->format('H:i:s') == '00:00:00')
                $to->endOfDay();

            $query->whereBetween('created_at', [$from, $to]);
        }

        $transactions = $query->with(['staff:id,user_id,account_name,account_number'])->latest()->paginate(10);

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
        return response()->json(['message' => 'success', 'data' => $accounts], 200);
    }
}
