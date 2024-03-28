<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\User;
use App\Services\LogService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FinanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $company_id = $this->sessionDetails('company_id');
        $query = Transaction::where(['company_id' => $company_id]);

        $from = $request->input('from');
        $to = $request->input('to');
        if ($from && $to) {
            $from = Carbon::parse($request->input('from'));

            $to = Carbon::parse($request->input('to'));
            if ($to->format('H:i:s') == '00:00:00')
                $to->endOfDay();

            $query->whereBetween('created_at', [$from, $to]);
        }

        $transactions = $query->latest()->paginate(20);
        return response()->json(['message' => 'Success', 'data' => $transactions], 200);
    }

        // $company_id = $this->sessionDetails('company_id');

    // }

}
