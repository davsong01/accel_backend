<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\StaffDisengagement;
use App\Models\User;
use App\Services\LogService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StaffDisengagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list = StaffDisengagement::with([
            'createdBy:id,user_id',
            'createdBy.user:id,first_name,middle_name,last_name,avatar,email',
            'disengaged:id,user_id',
            'disengaged.user:id,first_name,middle_name,last_name,avatar,email',
        ])->where('company_id', $this->sessionDetails('company_id'))->latest()->paginate(20);
        return response()->json(['message' => 'Success', 'data' => $list], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $company_id = $this->sessionDetails('company_id');

        $from  = $request->from ?: now();

        $validator = Validator::make($request->all(), [
            'reason' => 'required|string',
            'staff_id' => 'required|exists:staff,id,company_id,' . $company_id,
            'status' => 'in:ACTIVE,INACTIVE',
            'from' => 'date',
            'to' => 'date|required_if:until_further_notice,false' . '|after:' . $from,

        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $user = auth()->user();
        $admin = Staff::where('user_id', $user->id)->first();
        $disengaged = Staff::where('id', $request->staff_id)->first();

        $attributes = [
            ...$validator->validated(),
            'company_id' => $company_id,
            'created_by' => $admin->id,

        ];
        $created = StaffDisengagement::create($attributes);

        LogService::admin($user->id, $disengaged->user_id, 'Disengaged a staff (' . $created->id  . ')', 'CREATE');
        return response()->json(['message' => 'Created successfully', 'data' => $created], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(StaffDisengagement $disengagement)
    {
        return response()->json(['message' => 'Fetched successfully', 'data' => $disengagement->load([
            'createdBy:id,user_id',
            'createdBy.user:id,first_name,middle_name,last_name,avatar,email',
            'disengaged:id,user_id',
            'disengaged.user:id,first_name,middle_name,last_name,avatar,email',
        ])], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StaffDisengagement $disengagement)
    {
        $validator = Validator::make($request->all(), [
            'to' => 'date|before:' . $$disengagement->from,
            'status' => 'in:ACTIVE,INACTIVE'

        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $user = auth()->user();
        $disengaged = Staff::where('id', $request->staff)->first();

        $disengagement->update($validator->validated());

        LogService::admin($user->id, $disengaged->user_id, 'Updated a disengaged staff (' . $$disengagement->id  . ')', 'UPDATE');
        return response()->json(['message' => 'Updated successfully', 'data' => $disengagement], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StaffDisengagement $disengagement)
    {
        $user = auth()->user();
        $disengaged = Staff::where('id', $disengagement->staff_id)->first();

        LogService::admin($user->id, $disengaged->user_id, 'Delete a disengaged staff (' . $disengagement->id  . ')', 'DELETE');

        $disengagement->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }


    /**
     * Search a listing of resource.
     */
    public function search(Request $request)
    {
        $params = $request->only(['status']);
        $company_id = $this->sessionDetails('company_id');

        $query = StaffDisengagement::with([
            'createdBy:id,user_id',
            'createdBy.user:id,first_name,middle_name,last_name,avatar,email',
            'disengaged:id,user_id',
            'disengaged.user:id,first_name,middle_name,last_name,avatar,email',
        ])->where('company_id', $company_id)->where($params);

        $from = $request->input('from');
        $to = $request->input('to');

        if ($from && $to) {
            $from = Carbon::parse($request->input('from'));

            $to = Carbon::parse($request->input('to'));
            if ($to->format('H:i:s') == '00:00:00')
                $to->endOfDay();

            $query->whereBetween('created_at', [$from, $to]);
        }

        if ($request->input('created_by') && $name = $request->input('created_by')) {
            $users = User::with(['staff'])->where('company_id', $company_id)->whereNotNull('company_id')
                ->where('first_name', 'LIKE', '%' . $name . '%')
                ->orWhere('last_name', 'LIKE', '%' . $name . '%')
                ->orWhere('middle_name', 'LIKE', '%' . $name . '%')->get();

            $created_by_ids = $users->whereNotNull('staff.id')->pluck('staff.id')->toArray();
            $query->whereIn('created_by', $created_by_ids);
        }

        if ($request->input('disengaged') && $name = $request->input('disengaged')) {
            $users = User::with(['staff'])->where('company_id', $company_id)->whereNotNull('company_id')
                ->where('first_name', 'LIKE', '%' . $name . '%')
                ->orWhere('last_name', 'LIKE', '%' . $name . '%')
                ->orWhere('middle_name', 'LIKE', '%' . $name . '%')->get();

            $staff_ids = $users->whereNotNull('staff.id')->pluck('staff.id')->toArray();
            $query->whereIn('staff_id', $staff_ids);
        }

        $staffDisengagement = $query->latest()->paginate(20);
        return response()->json(['message' => 'success', 'data' => $staffDisengagement], 200);
    }
}
