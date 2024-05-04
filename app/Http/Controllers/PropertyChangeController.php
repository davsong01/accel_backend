<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\PropertyChange;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PropertyChangeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = $this->sessionDetails('company_id');
        $list = PropertyChange::where('company_id', $company_id)->latest()->paginate();
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
            'original_customer_contract_id' => ['required', "exists:customer_contracts,id,customer_id,$request->customer_id"],
            'new_customer_contract_id' => ['required', "exists:customer_contracts,id,customer_id,$request->customer_id"],
            'new_customer_contract_id' => [
                'required', "exists:customer_contracts,id,customer_id,$request->customer_id",
                fn ($attribute, $value, $fail) =>
                $value === $request->original_customer_contract_id ?
                    $fail('The new contract must be different from the original contract.') : null,
            ],
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

        $created = PropertyChange::create($attributes);

        LogService::admin($user->id, $customer->user->id, 'Create a property change (' . $created->id  . ')', 'CREATE');
        return response()->json(['message' => 'Created successfully', 'data' => $created], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(PropertyChange $property_change)
    {
        return response()->json(['message' => 'Fetched successfully', 'data' => $property_change], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PropertyChange $property_change)
    {
        $company_id = $this->sessionDetails('company_id');
        $user = auth()->user();

        $customer = $property_change->customer;

        $validator = Validator::make($request->all(), [
            'branch_id' => ['sometimes', "exists:branches,id,company_id,$company_id"],
            'customer_id' => ['sometimes', "exists:customers,id,company_id,$company_id"],
            'original_customer_contract_id' => ['sometimes', "exists:customer_contracts,id,customer_id,$request->customer_id"],
            'new_customer_contract_id' => ['sometimes', "exists:customer_contracts,id,customer_id,$request->customer_id"],
            'new_customer_contract_id' => [
                'sometimes', "exists:customer_contracts,id,customer_id,$request->customer_id",
                fn ($attribute, $value, $fail) =>
                $value === $request->original_customer_contract_id ?
                    $fail('The new contract must be different from the original contract.') : null,
            ],
            'comment' => ['sometimes', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $attributes = $validator->validated();
        $property_change->update($attributes);

        LogService::admin($user->id, $customer->user->id, 'Updated a property change (' . $property_change->id  . ')', 'UPDATE');
        return response()->json(['message' => 'Updated successfully', 'data' => $property_change], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PropertyChange $property_change)
    {
        $user = auth()->user();
        $customer = $property_change->customer;

        LogService::admin($user->id, $customer->user->id, 'Delete a property change (' . $property_change->id  . ')', 'DELETE');

        $property_change->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }


    /**
     * Search a listing of resource.
     */
    public function search(Request $request)
    {
        $params = $request->only(['branch_id', 'customer_id', 'original_customer_contract_id', 'new_customer_contract_id', 'company_id']);
        $company_id = $this->sessionDetails('company_id');

        $query = PropertyChange::where('company_id', $company_id)->where($params);

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


        $recipients = $query->latest()->paginate(20);
        return response()->json(['message' => 'success', 'data' => $recipients], 200);
    }

}
