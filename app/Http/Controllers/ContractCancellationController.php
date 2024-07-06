<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ContractCancellation;
use App\Models\Customer;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContractCancellationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = $this->sessionDetails('company_id');
        $list = ContractCancellation::where('company_id', $company_id)->latest()->paginate();
        return response()->json(['message' => 'Success', 'data' => $list], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $company_id = $this->sessionDetails('company_id');

        $validator = Validator::make($request->all(), [
            'branch_id' => ['required', "exists:branches,id,company_id,$company_id"],
            'customer_id' => ['required', "exists:customers,id,company_id,$company_id"],
            'customer_contract_id' => ['required', "exists:customer_contracts,id,customer_id,$request->customer_id"],
            'comment' => ['sometimes', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $user =  auth()->user();
        $customer = Customer::find($request->customer_id);

        $attributes = array_merge($validator->validated(), ['company_id' => $company_id]);

        $created = ContractCancellation::create($attributes);
        $created->special_request()->create(['type' => 'Contract Cancellation', 'company_id' => $company_id]);
        
        LogService::admin($user->id, $customer->user->id, 'Create a contract cancellation (' . $created->id  . ')', 'CREATE');
        return response()->json(['message' => 'Created successfully', 'data' => $created], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(ContractCancellation $contract_cancellation)
    {
        return response()->json(['message' => 'Fetched successfully', 'data' => $contract_cancellation], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContractCancellation $contract_cancellation)
    {
        $company_id = $this->sessionDetails('company_id');
        $user = auth()->user();

        $customer = $contract_cancellation->customer;

        $validator = Validator::make($request->all(), [
            'branch_id' => ['sometimes', "exists:branches,id,company_id,$company_id"],
            'customer_id' => ['sometimes', "exists:customers,id,company_id,$company_id"],
            'customer_contract_id' => ['sometimes', "exists:customer_contracts,id,customer_id,$request->customer_id"],
            'comment' => ['sometimes', 'string'],
            'status' => ['sometimes', 'in:PENDING,DECLINED,APPROVED']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $attributes = $validator->validated();
        $contract_cancellation->update($attributes);

        LogService::admin($user->id, $customer->user->id, 'Updated a contract cancellation (' . $contract_cancellation->id  . ')', 'UPDATE');
        return response()->json(['message' => 'Updated successfully', 'data' => $contract_cancellation], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContractCancellation $contract_cancellation)
    {
        $user = auth()->user();
        $customer = $contract_cancellation->customer;

        LogService::admin($user->id, $customer->user->id, 'Delete a contract cancellation (' . $contract_cancellation->id  . ')', 'DELETE');

        $contract_cancellation->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }


    /**
     * Search a listing of resource.
     */
    public function search(Request $request)
    {
        $params = $request->only(['branch_id', 'customer_id', 'original_customer_contract_id', 'new_customer_contract_id', 'company_id']);
        $company_id = $this->sessionDetails('company_id');

        $query = ContractCancellation::where('company_id', $company_id)->where($params);

        if ($name = $request->input('branch_name')) {
            $branches = Branch::where('company_id', $company_id)->whereNotNull('company_id')
                ->where('name', 'LIKE', '%' . $name . '%')
                ->get();

            $branch_ids = $branches->whereNotNull('id')->pluck('id')->toArray();
            $query->whereIn('branch_id', $branch_ids);
        }

        if ($name = $request->input('customer_name')) {
            $users = User::with(['customer'])->where('company_id', $company_id)->whereNotNull('company_id')
                ->where('first_name', 'LIKE', '%' . $name . '%')
                ->orWhere('last_name', 'LIKE', '%' . $name . '%')
                ->orWhere('middle_name', 'LIKE', '%' . $name . '%')->get();

            $customer_ids = $users->whereNotNull('customer.id')->pluck('customer.id')->toArray();
            $query->whereIn('customer_id', $customer_ids);
        }

        $contract_cancellations = $query->latest()->paginate(20);
        return response()->json(['message' => 'Success', 'data' => $contract_cancellations], 200);
    }
}
