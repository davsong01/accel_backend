<?php

namespace App\Http\Controllers;

use App\Models\TransactionType;
use App\Services\LogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionTypeController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $list = TransactionType::with(['purposes'])->where('company_id', sessionDetails('company_id'))->latest()->paginate(20);
        return response()->json(['message' => 'Success', 'data' => $list], 200);
    }

    /**
     * Search a listing of resource.
     */
    public function search(Request $request)
    {
        $name = $request->input('name');

        $query = TransactionType::with(['purposes'])->where('company_id', sessionDetails('company_id'))
        ->where('name', 'LIKE', "%$name%")->latest()->paginate(20);

        return response()->json(['message' => 'Success', 'data' => $query], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'customer_details' => $request->customer_contract_details === 'Required' ? ['required', 'in:Required'] : ['in:Required,Not Required'],
            'customer_contract_details' => 'in:Required,Not Required',
            'staff_details' => 'in:Required,Not Required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $attributes = [...$validator->validated(), 'company_id' => sessionDetails('company_id')];
        $created = TransactionType::create($attributes);

        LogService::admin(auth()->user()->id, null, 'Create transaction type (' . $created->id  . ')' , 'CREATE');

        return response()->json(['message' => 'Created successfully', 'data' => $created], 200);
    }

    /**
     * Show the specified resource in storage.
     */
    public function show(Request $request, TransactionType $type)
    {
        return response()->json(['message' => 'Updated successfully', 'data' => $type->load(['purposes'])], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TransactionType $type)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'customer_details' => 'in:Required,Not Required',
            'customer_contract_details' => 'in:Required,Not Required',
            'staff_details' => 'in:Required,Not Required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $type->update($validator->validated());

        LogService::admin(auth()->user()->id, null, 'Update transaction type (' . $type->id  . ')' , 'UPDATE');
        return response()->json(['message' => 'Updated successfully', 'data' => $type->load('purposes')], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TransactionType $type)
    {
        if($type->purposes->count()){
            return response()->json([
                'error' => 'Ops, looks like the transaction type is in use',
                'data' => null,
            ], 409);
        }
        LogService::admin(auth()->user()->id, null, 'Delete transaction type (' . $type->id  . ')' , 'DELETE');

        $type->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }
}
