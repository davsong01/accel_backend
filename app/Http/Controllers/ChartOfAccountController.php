<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ChartOfAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class ChartOfAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coa = ChartOfAccount::with('account_category:id,name', 'account_type:id,type')->where('company_id', $this->sessionDetails('company_id'))->orderBy('created_at', 'desc')->get();
        return response()->json(['message' => 'success', 'data' => $coa], 200);
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
            'accounttype_id' => 'required|exists:account_types,id,company_id,' . $this->sessionDetails('company_id'),
            'account_category_id' => 'required|exists:account_categories,id,company_id,' . $this->sessionDetails('company_id'),
            'currency' => 'required',
            'name' => 'required',
            'input_method' => 'required|in:direct,manual',
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
            'input_method' => $request->input_method,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Created successfully', 'data' => $account_class], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(ChartOfAccount $chartOfAccount)
    {
        if ($chartOfAccount) {
            return response()->json(['message' => 'Retrieved successfully', 'data' => $chartOfAccount->load('account_category:id,name', 'account_type:id,type')], 200);
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
            'accounttype_id' => 'required|exists:account_types,id,company_id,' . $this->sessionDetails('company_id'),
            'account_category_id' => 'required|exists:account_categories,id,company_id,' . $this->sessionDetails('company_id'),
            'currency' => 'required',
            'name' => 'required',
            'input_method' => 'required|in:direct,manual',
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
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Updated successfully', 'data' => []], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChartOfAccount $chartOfAccount)
    {
        $chartOfAccount->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => []], 200);
    }
}
