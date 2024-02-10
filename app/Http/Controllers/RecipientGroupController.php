<?php

namespace App\Http\Controllers;

use App\Models\Estate;
use App\Models\Location;
use App\Models\RecipientGroup;
use App\Models\Staff;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecipientGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list = RecipientGroup::where('company_id', 1)->get();
        return response()->json(['message' => 'Success', 'data' => $list], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $company_id = $this->sessionDetails('company_id');

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:250|min:2',
            'all_customers' => 'boolean',
            'customer_status' => 'in:inactive,active',
            'subscriber_status' => 'in:inactive,active',
            'completed_subscriber_status' => 'in:inactive,active',
            'location_ids' => 'array',
            'location_ids.*' => "exists:locations,id,company_id,$company_id",
            'estate_ids' => 'array',
            'estate_ids.*' => "exists:estates,id,company_id,$company_id",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $user = auth()->user();
        $admin = Staff::where('user_id', $user->id)->first();

        $attributes = [
            ...$validator->validated(),
            'company_id' => $company_id,
            'created_by' => $admin->id,

        ];
        $created = RecipientGroup::create($attributes);

        LogService::admin($user->id, null, 'Add new recipient group (' . $created->id  . ')', 'CREATE');
        return response()->json(['message' => 'Created successfully', 'data' => $created], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(RecipientGroup $group)
    {
        return response()->json(['message' => 'Fetched successfully', 'data' => $group], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RecipientGroup $group)
    {
        $company_id = $this->sessionDetails('company_id');

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:250|min:2',
            'all_customers' => 'boolean',
            'customer_status' => 'in:inactive,active',
            'subscriber_status' => 'in:inactive,active',
            'completed_subscriber_status' => 'in:inactive,active',
            'location_ids' => 'array',
            'location_ids.*' => "exists:locations,id,company_id,$company_id",
            'estate_ids' => 'array',
            'estate_ids.*' => "exists:estates,id,company_id,$company_id",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $user = auth()->user();

        $group->update($validator->validated());

        LogService::admin($user->id, null, 'Updated a recipient group (' . $group->id  . ')', 'UPDATE');
        return response()->json(['message' => 'Updated successfully', 'data' => $group], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RecipientGroup $group)
    {
        $user = auth()->user();

        LogService::admin($user->id, null, 'Delete a recipient group (' . $group->id  . ')', 'DELETE');

        $group->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }


    /**
     * Search a listing of resource.
     */
    public function search(Request $request)
    {
        $params = $request->only(['customer_status', 'all_customers', 'subscriber_status', 'completed_subscriber_status']);
        $company_id = $this->sessionDetails('company_id');

        $query = RecipientGroup::where($params);

        if ($name = $request->input('name'))
            $query->where('name', 'LIKE', '%' . $name . '%');

        if ($request->input('created_by') && $name = $request->input('created_by')) {
            $users = User::with(['staff'])->where('company_id', $company_id)->whereNotNull('company_id')
                ->where('first_name', 'LIKE', '%' . $name . '%')
                ->orWhere('last_name', 'LIKE', '%' . $name . '%')
                ->orWhere('middle_name', 'LIKE', '%' . $name . '%')->get();

            $created_by_ids = $users->whereNotNull('staff.id')->pluck('staff.id')->toArray();
            $query->whereIn('created_by', $created_by_ids);
        }

        if ($request->input('locations') && $name = $request->input('locations')) {
            $locations = Location::where('company_id', $company_id)->whereNotNull('company_id')
                ->where('name', 'LIKE', '%' . $name . '%')
                ->get();

            $locations_ids = $locations->whereNotNull('id')->pluck('id')->toArray();
            $query->whereJsonContains('location_ids', $locations_ids);
        }

        if ($request->input('estates') && $name = $request->input('estates')) {
            $estates = Estate::where('company_id', $company_id)->whereNotNull('company_id')
                ->where('name', 'LIKE', '%' . $name . '%')
                ->get();

            $estates_ids = $estates->whereNotNull('id')->pluck('id')->toArray();
            $query->whereJsonContains('estate_ids', $estates_ids);
        }


        $recipients = $query->latest()->paginate(20);
        return response()->json(['message' => 'success', 'data' => $recipients], 200);
    }
}
