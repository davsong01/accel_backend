<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\LeaveAndAbenceManagement;
use Illuminate\Support\Facades\Validator;


class LeaveAndAbenceManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id = null)
    {
        $lam = LeaveAndAbenceManagement::where('company_id', $this->sessionDetails('company_id'));

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

        $lam = $lam->orderBy('created_at', 'DESC')->paginate(20);

        if ($lam->count() > 0) {
            return response()->json(['message' => 'Retrieved Successfully', 'data' => $lam], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => null], 200);
        }
    }

    public function staffLeaveAndAbsence(Request $request, $staff_id)
    {
        $lam = LeaveAndAbenceManagement::where('company_id', $this->sessionDetails('company_id'))->where('staff_id', $staff_id);

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

        $lam = $lam->orderBy('created_at', 'DESC')->paginate(20);

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
            'delegate_duty' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $lam = LeaveAndAbenceManagement::updateOrcreate([
            'staff_id' => isset($internal) ? $request['staff_id'] : $this->sessionDetails('staff_id'),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'period' => $request->period,
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
        $lam = LeaveAndAbenceManagement::find($id);
        $lam->update(['status' => 'approved']);

        return response()->json(['message' => 'Approved successfully', 'data' => $lam], 200);
    }

    public function declineLeaveAndAbsence($id)
    {
        $lam = LeaveAndAbenceManagement::find($id);
        $lam->update(['status' => 'declined']);

        return response()->json(['message' => 'Declined successfully', 'data' => $lam], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveAndAbenceManagement $leaveAndAbenceManagement)
    {
        //
    }
}
