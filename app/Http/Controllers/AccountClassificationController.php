<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AccountClassification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class AccountClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $account_classification = AccountClassification::where('company_id', $this->sessionDetails('company_id'))->orderBy('created_at', 'desc')->get();
        return response()->json(['message' => 'success', 'data' => $account_classification], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $account_class = AccountClassification::updateOrcreate([
            'class' => $request->class,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Created successfully', 'data' => $account_class], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(AccountClassification $accountClassification)
    {
        if($accountClassification){
            return response()->json(['message' => 'Retrieved successfully', 'data' => $accountClassification], 200);
        }else{
            return response()->json(['message' => 'No records found', 'data' => null], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccountClassification $accountClassification): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccountClassification $accountClassification)
    {
        $validator = Validator::make($request->all(), [
            'class' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $accountClassification->update([
            'class' => $request->class,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Updated successfully', 'data' => $accountClassification], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountClassification $accountClassification)
    {
        $accountClassification->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }
}
