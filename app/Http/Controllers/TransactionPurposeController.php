<?php

namespace App\Http\Controllers;

use App\Models\TransactionPurpose;
use App\Services\LogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionPurposeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $list = TransactionPurpose::with(['transactionType'])->where('company_id', sessionDetails('company_id'))->latest()->paginate(20);
        return response()->json(['message' => 'Success', 'data' => $list], 200);
    }

    /**
     * Search a listing of resource.
     */
    public function search(Request $request): JsonResponse
    {
        $name = $request->input('name');

        $query = TransactionPurpose::where('company_id', sessionDetails('company_id'))
        ->where('name', 'LIKE', "%$name%")->latest()->paginate(20);

        return response()->json(['message' => 'Success', 'data' => $query], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $company_id = sessionDetails('company_id'); 
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'transaction_type_id' => 'required|exists:transaction_types,id,company_id,' . $company_id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $attributes = [...$validator->validated(), 'company_id' => $company_id];
        $created = TransactionPurpose::create($attributes);

        LogService::admin(auth()->user()->id, null, 'Create transaction purpose (' . $created->id  . ')' , 'CREATE');
        return response()->json(['message' => 'Created successfully', 'data' => $created], 200);
    }


    /**
     * Show the specified resource in storage.
     */
    public function show(Request $request, TransactionPurpose $purpose)
    {
        return response()->json(['message' => 'Updated successfully', 'data' => $purpose->load(['transactionType'])], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransactionPurpose $purpose)
    {

        $company_id = sessionDetails('company_id'); 
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'transaction_type_id' => 'exists:transaction_types,id,company_id,' . $company_id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $purpose->update($validator->validated());

        LogService::admin(auth()->user()->id, null, 'Update transaction purpose (' . $purpose->id  . ')' , 'UPDATE');

        return response()->json(['message' => 'Updated successfully', 'data' => $purpose->load(['transactionType'])], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionPurpose $purpose)
    {
        LogService::admin(auth()->user()->id, null, 'Delete transaction purpose (' . $purpose->id  . ')' , 'DELETE');

        $purpose->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }

}
