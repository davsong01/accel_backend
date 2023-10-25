<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\LeaveAndAbsenceSetting;
use App\Models\LeaveAndAbsenceManagement;
use Illuminate\Support\Facades\Validator;


class LeaveAndAbsenceManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id = null)
    {
        $lam = LeaveAndAbsenceManagement::where('company_id', $this->sessionDetails('company_id'));

        if (isset($request->type) && !empty($request->type)) {
            $lam = $lam->where('type', $request->type);
        }
        if (isset($id) && !empty($id)) {
            $lam = $lam->where('id', $id)->first();
            if ($lam) {
                return response()->json(['message' => 'Retrieved Successfully', 'data' => $lam], 200);
            } else {
                return response()->json(['message' => 'No records found', 'data' => null], 200);
            }
        }

        $lam = $lam->orderBy('created_at', 'DESC')->paginate(20)->withQueryString();

        if ($lam->count() > 0) {
            return response()->json(['message' => 'Retrieved Successfully', 'data' => $lam], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => null], 200);
        }
    }

    public function staffLeaveAndAbsence(Request $request, $staff_id)
    {
        $lam = LeaveAndAbsenceManagement::where('company_id', $this->sessionDetails('company_id'))->where('staff_id', $staff_id);

        if (isset($request->type) && !empty($request->type)) {
            $lam = $lam->where('type', $request->type);
        }

        if (isset($id) && !empty($id)) {
            $lam = $lam->where('id', $id)->first();
            if ($lam) {
                return response()->json(['message' => 'Retrieved Successfully', 'data' => $lam], 200);
            } else {
                return response()->json(['message' => 'No records found', 'data' => null], 200);
            }
        }

        $lam = $lam->orderBy('created_at', 'DESC')->paginate(20)->withQueryString();

        if ($lam->count() > 0) {
            return response()->json(['message' => 'Retrieved Successfully', 'data' => $lam], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => null], 200);
        }
    }

    public function store(Request $request, $internal = null)
    {
        $validator = Validator::make($request->all(), [
            'period' => 'required',
            'reason' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'delegate_duty' => 'required|exists:staff,id,company_id,' . $this->sessionDetails('company_id'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $start = Carbon::parse($request->start_date . ' 00:00:00');
        $end = Carbon::parse($request->end_date . ' 23:59:00');

        if ($start > $end) {
            return response()->json([
                'error' => 'Start date cannot be greater than end date',
                'data' => null,
            ], 412);
        }

        $period = $end->diffInDays($start);

        $lam = LeaveAndAbsenceManagement::updateOrcreate([
            'staff_id' => isset($internal) ? $request['staff_id'] : $this->sessionDetails('staff_id'),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'period' => $period,
            'reason' => $request->reason,
            'type' => $request->type ?? 'leave',
            'delegate_duty' => $request->delegate_duty,
            'status' => 'pending',
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Created successfully', 'data' => $lam], 200);
    }

    public function approveLeaveAndAbsence($id)
    {
        $lam = LeaveAndAbsenceManagement::find($id);

        if ($lam->status == 'Approved') {
            return response()->json(['message' => 'Already Approved', 'data' => null], 200);
        }

        $lamb = $lam;
        $lam->update(['status' => 'approved']);
        $data = [
            'title' => 'Leave Approved',
            'content' => 'Your leaved has been approved by ' . Auth::user()->first_name . ' ' . Auth::user()->last_name,
            'link' => url('/') . '/single-staff-leave-and-absence/api/admin/' . $lam->staff_id . '?type=leave',
            'user_type' => 'staff',
            'staff_id' => $lam->staff_id,
            'company_id' => $this->sessionDetails('company_id')
        ];

        $this->notify($data);
        return response()->json(['message' => 'Approved successfully', 'data' => $lam], 200);
    }

    public function declineLeaveAndAbsence($id)
    {
        $lam = LeaveAndAbsenceManagement::find($id);
        $lam->update(['status' => 'declined']);

        return response()->json(['message' => 'Declined successfully', 'data' => $lam], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveAndAbsenceManagement $LeaveAndAbsenceManagement)
    {
        //
    }

    public function updateLeaveAndAbsenceSetting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:leave,absence',
            'period' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $company_id = $this->sessionDetails('company_id');

        $setting = LeaveAndAbsenceSetting::updateOrCreate(['company_id' => $company_id, 'type' => $request->type], [
            'type' => $request->type,
            'period' => $request->period
        ]);

        return response()->json(['message' => 'Updated successfully', 'data' => null], 200);
    }

    public function getLeaveAndAbsenceSetting(Request $request)
    {
        $settings = LeaveAndAbsenceSetting::where('company_id', $this->sessionDetails('company_id'));

        if (!empty($request->type)) {
            $settings->where('type', $request->type);
        }

        return response()->json(['message' => 'Retrieved successfully', 'data' => $settings->get()], 200);
    }
}
