<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ChartOfAccount;
use App\Services\LogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class ChartOfAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $coa = ChartOfAccount::with('currency', 'account_category:id,name', 'account_type:id,type')->orderBy('created_at', 'desc');

        if (!empty($request->status)) {
            $coa = $coa->where('status', $request->status);
        }

        $coa = $coa->get();
        return response()->json(['message' => 'Success', 'data' => $coa], 200);
    }


    public function modifyChartOfAccount($id)
    {
        $coa = ChartOfAccount::where('id', $id)->first();
        if ($coa) {
            if ($coa->status == 'active') {
                $coa->update(['status' => 'inactive']);
            } else {
                $coa->update(['status' => 'active']);
            }
            LogService::admin(auth()->user()->id, NULL, 'Modify chart of account status ('. $coa->id .')', 'UPDATE');
        }

        return response()->json(['message' => 'Successfully updated', 'data' => []], 200);
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
            'accounttype_id' => 'required|exists:account_types,id,company_id,' . sessionDetails('company_id'),
            'account_category_id' => 'required|exists:account_categories,id,company_id,' . sessionDetails('company_id'),
            'currency' => 'required|exists:currencies,id',
            'name' => 'required',
            'account_number' => 'nullable',
            'input_method' => 'required|in:direct,indirect',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $account_class = ChartOfAccount::updateOrcreate([
            'accounttype_id' => $request->accounttype_id,
            'account_category_id' => $request->account_category_id,
            'currency' => $request->currency,
            'name' => $request->name,
            'account_number' => $request->account_number,
            'input_method' => $request->input_method,
            'company_id' => sessionDetails('company_id'),
        ]);

        LogService::admin(auth()->user()->id, NULL, 'Create chart of account status ('. $account_class->id .')', 'CREATE');
        return response()->json(['message' => 'Created successfully', 'data' => $account_class], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(ChartOfAccount $chartOfAccount)
    {
        if ($chartOfAccount) {
            return response()->json(['message' => 'Retrieved successfully', 'data' => $chartOfAccount->load('currency', 'account_category:id,name', 'account_type:id,type')], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => $chartOfAccount], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChartOfAccount $chartOfAccount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChartOfAccount $chartOfAccount)
    {
        $validator = Validator::make($request->all(), [
            'accounttype_id' => 'required|exists:account_types,id,company_id,' . sessionDetails('company_id'),
            'account_category_id' => 'required|exists:account_categories,id,company_id,' . sessionDetails('company_id'),
            'currency' => 'required',
            'name' => 'required',
            'input_method' => 'required|in:direct,indirect',
            'account_number' => 'nullable',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $chartOfAccount->update([
            'accounttype_id' => $request->accounttype_id,
            'account_category_id' => $request->account_category_id,
            'currency' => $request->currency,
            'name' => $request->name,
            'input_method' => $request->input_method,
            'status' => $request->status,
            'account_number' => $request->account_number,
            'company_id' => sessionDetails('company_id'),
        ]);

        LogService::admin(auth()->user()->id, NULL, 'Update a chart of account status ('. $chartOfAccount->id .')', 'UPDATE');
        return response()->json(['message' => 'Updated successfully', 'data' => []], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChartOfAccount $chartOfAccount)
    {
        LogService::admin(auth()->user()->id, NULL, 'Delete chart of account status ('. $chartOfAccount->id .')', 'DELETE');

        $chartOfAccount->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => []], 200);
    }
}
