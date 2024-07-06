<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ChangeOfOwnership;
use App\Models\Customer;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChangeOfOwnershipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = $this->sessionDetails('company_id');
        $list = ChangeOfOwnership::where('company_id', $company_id)->latest()->paginate();
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
            'original_owner_id' => ['required', "exists:customers,id,company_id,$company_id"],
            'customer_contract_id' => ['required', "exists:customer_contracts,id,customer_id,$request->original_owner_id"],
            'new_owner_id' => [
                'required',
                "exists:customers,id,company_id,$company_id",
                fn ($attribute, $value, $fail) =>
                $value === $request->original_owner_id ?
                    $fail('The new owner must be different from the original owner.') : null,
            ],
            'comment' => ['sometimes', 'string'],
            'amount' => ['required', 'min:1', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $user =  auth()->user();
        $customer = Customer::find($request->original_owner_id);

        $attributes = array_merge($validator->validated(), ['company_id' => $company_id]);

        $created = ChangeOfOwnership::create($attributes);
        $created->special_request()->create(['type' => 'Change of Ownership', 'company_id' => $company_id]);

        LogService::admin($user->id, $customer->user->id, 'Create a change of ownership (' . $created->id  . ')', 'CREATE');
        return response()->json(['message' => 'Created successfully', 'data' => $created], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(ChangeOfOwnership $change_of_ownership)
    {
        return response()->json(['message' => 'Fetched successfully', 'data' => $change_of_ownership], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChangeOfOwnership $change_of_ownership)
    {
        $company_id = $this->sessionDetails('company_id');
        $user = auth()->user();

        $customer = Customer::find($change_of_ownership->original_owner_id);

        $validator = Validator::make($request->all(), [
            'branch_id' => ['sometimes', "exists:branches,id,company_id,$company_id"],
            'original_owner_id' => ['sometimes', "exists:customers,id,company_id,$company_id"],
            'customer_contract_id' => ['sometimes', "exists:customer_contracts,id,customer_id,$request->original_owner_id"],
            'new_owner_id' => ['sometimes', "exists:customers,id,company_id,$company_id"],
            'comment' => ['sometimes', 'string'],
            'amount' => ['sometimes', 'min:1', 'integer'],
            'status' => ['sometimes', 'in:PENDING,DECLINED,APPROVED']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $attributes = $validator->validated();
        $change_of_ownership->update($attributes);

        LogService::admin($user->id, $customer->user->id, 'Updated a change of ownership (' . $change_of_ownership->id  . ')', 'UPDATE');
        return response()->json(['message' => 'Updated successfully', 'data' => $change_of_ownership], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChangeOfOwnership $change_of_ownership)
    {
        $user = auth()->user();
        $customer = Customer::find($change_of_ownership->original_owner_id);

        LogService::admin($user->id, $customer->user->id, 'Delete a change of ownership (' . $change_of_ownership->id  . ')', 'DELETE');

        $change_of_ownership->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }


    /**
     * Search a listing of resource.
     */
    public function search(Request $request)
    {
        $params = $request->only(['branch_id', 'original_owner_id', 'customer_contract_id', 'new_owner_id', 'amount']);
        $company_id = $this->sessionDetails('company_id');

        $query = ChangeOfOwnership::where('company_id', $company_id)->where($params);

        if ($name = $request->input('branch_name')) {
            $branches = Branch::where('company_id', $company_id)->whereNotNull('company_id')
                ->where('name', 'LIKE', '%' . $name . '%')
                ->get();

            $branch_ids = $branches->whereNotNull('id')->pluck('id')->toArray();
            $query->whereIn('branch_id', $branch_ids);
        }

        if ($name = $request->input('original_owner_name')) {
            $users = User::with(['customer'])->where('company_id', $company_id)->whereNotNull('company_id')
                ->where('first_name', 'LIKE', '%' . $name . '%')
                ->orWhere('last_name', 'LIKE', '%' . $name . '%')
                ->orWhere('middle_name', 'LIKE', '%' . $name . '%')->get();

            $original_owner_ids = $users->whereNotNull('customer.id')->pluck('customer.id')->toArray();
            $query->whereIn('original_owner_id', $original_owner_ids);
        }

        if ($name = $request->input('new_owner_id_name')) {
            $users = User::with(['customer'])->where('company_id', $company_id)->whereNotNull('company_id')
                ->where('first_name', 'LIKE', '%' . $name . '%')
                ->orWhere('last_name', 'LIKE', '%' . $name . '%')
                ->orWhere('middle_name', 'LIKE', '%' . $name . '%')->get();

            $new_owner_ids = $users->whereNotNull('customer.id')->pluck('customer.id')->toArray();
            $query->whereIn('new_owner_id', $new_owner_ids);
        }

        $recipients = $query->latest()->paginate(20);
        return response()->json(['message' => 'Success', 'data' => $recipients], 200);
    }
}
