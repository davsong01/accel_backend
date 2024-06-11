<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\CustomerRefund;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerRefundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = $this->sessionDetails('company_id');
        $list = CustomerRefund::where('company_id', $company_id)->latest()->paginate();
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
            'termination_charge' => ['required', 'integer'],
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

        $created = CustomerRefund::create($attributes);
        $created->special_request()->create(['type' => 'Customer Refund', 'company_id' => $company_id]);

        LogService::admin($user->id, $customer->user->id, 'Create a customer refund (' . $created->id  . ')', 'CREATE');
        return response()->json(['message' => 'Created successfully', 'data' => $created], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerRefund $customer_refund)
    {
        return response()->json(['message' => 'Fetched successfully', 'data' => $customer_refund], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerRefund $customer_refund)
    {
        $company_id = $this->sessionDetails('company_id');
        $user = auth()->user();

        $customer = $customer_refund->customer;

        $validator = Validator::make($request->all(), [
            'branch_id' => ['sometimes', "exists:branches,id,company_id,$company_id"],
            'customer_id' => ['sometimes', "exists:customers,id,company_id,$company_id"],
            'customer_contract_id' => ['sometimes', "exists:customer_contracts,id,customer_id,$request->customer_id"],
            'termination_charge' => ['sometimes', 'integer'],
            'comment' => ['sometimes', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $attributes = $validator->validated();
        $customer_refund->update($attributes);

        LogService::admin($user->id, $customer->user->id, 'Updated a customer refund (' . $customer_refund->id  . ')', 'UPDATE');
        return response()->json(['message' => 'Updated successfully', 'data' => $customer_refund], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerRefund $customer_refund)
    {
        $user = auth()->user();
        $customer = $customer_refund->customer;

        LogService::admin($user->id, $customer->user->id, 'Delete a customer refund (' . $customer_refund->id  . ')', 'DELETE');

        $customer_refund->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }


    /**
     * Search a listing of resource.
     */
    public function search(Request $request)
    {
        $params = $request->only(['branch_id', 'customer_id', 'original_customer_contract_id', 'new_customer_contract_id', 'company_id']);
        $company_id = $this->sessionDetails('company_id');

        $query = CustomerRefund::where('company_id', $company_id)->where($params);

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

        $customer_refunds = $query->latest()->paginate(20);
        return response()->json(['message' => 'Success', 'data' => $customer_refunds], 200);
    }
}
