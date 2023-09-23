<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\TransactionTypeDefinition;
use Illuminate\Support\Facades\Validator;

class TransactionTypeDefinitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tdt = TransactionTypeDefinition::where('company_id', $this->sessionDetails('company_id'))->orderBy('created_at', 'desc')->get();
        return response()->json(['message' => 'success', 'data' => $tdt], 200);
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
            'transaction_type_id' => 'required',
            'set_rules' => 'required',
            'set_attributes' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $ttd = TransactionTypeDefinition::updateOrcreate([
            'transaction_type_id' => $request->transaction_type_id,
            'set_rules' => $request->set_rules,
            'set_attributes' => $request->set_attributes,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Created successfully', 'data' => $ttd], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(TransactionTypeDefinition $transactionTypeDefinition)
    {
        if ($transactionTypeDefinition) {
            return response()->json(['message' => 'Retrieved successfully', 'data' => $transactionTypeDefinition], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => null], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TransactionTypeDefinition $transactionTypeDefinition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransactionTypeDefinition $transactionTypeDefinition)
    {
        $validator = Validator::make($request->all(), [
            'transaction_type_id' => 'required',
            'set_rules' => 'required',
            'set_attributes' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $transactionTypeDefinition->update([
            'transaction_type_id' => $request->transaction_type_id,
            'set_rules' => $request->set_rules,
            'set_attributes' => $request->set_attributes,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Updated successfully', 'data' => $transactionTypeDefinition], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionTypeDefinition $transactionTypeDefinition)
    {
        $transactionTypeDefinition->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }
}
