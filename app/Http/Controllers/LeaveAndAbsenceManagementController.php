<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\LeaveAndAbsenceSetting;
use App\Models\LeaveAndAbsenceManagement;
use App\Services\LogService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Services\LeaveDateService;

class LeaveAndAbsenceManagementController extends Controller
{
    protected $leaveDateService;

    public function __construct(LeaveDateService $leaveDateService)
    {
        $this->leaveDateService = $leaveDateService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id = null)
    {
        if (\Request::route()->getPrefix() == 'api/admin/self-service') {
            $staff_id = Auth::user()->staff->id;
            $lam = LeaveAndAbsenceManagement::where('company_id', $this->sessionDetails('company_id'))->where('staff_id', $staff_id);
        } else {
            $lam = LeaveAndAbsenceManagement::where('company_id', $this->sessionDetails('company_id'));
        }

        if (isset($request->type) && !empty($request->type)) {
            $lam = $lam->where('type', $request->type);
        }

        if (isset($request->status) && !empty($request->status)) {
            $lam = $lam->where('status', $request->status);
        }

        if($request->start_date && $request->end_date){
            // Convert search dates to Carbon objects for comparison
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            // Query leave applications that were created within the specified range
            $lam = $lam->whereBetween('created_at', [$startDate, $endDate]);
        }

        if (isset($id) && !empty($id)) {
            $lam = $lam->where('id', $id)->first();

            if (!empty($lam->staff_id)) {
                $lam->request_by = [
                    'first_name' => $lam->staff->user->first_name ?? '',
                    'middle_name' => $lam->staff->user->middle_name ?? '',
                    'last_name' => $lam->staff->user->last_name ?? '',
                    'id' => $lam->staff->id ?? '',
                ];
            }

            if ($lam) {
                return response()->json(['message' => 'Retrieved Successfully', 'data' => $lam], 200);
            } else {
                return response()->json(['message' => 'No records found', 'data' => $lam], 200);
            }
        }

        $lam = $lam->orderBy('created_at', 'DESC')->paginate(20)->withQueryString();

        foreach ($lam as $l) {
            if (!empty($l->staff_id)) {
                $l->request_by = [
                    'first_name' => $l->staff->user->first_name ?? '',
                    'middle_name' => $l->staff->user->middle_name ?? '',
                    'last_name' => $l->staff->user->last_name ?? '',
                    'id' => $l->staff->id ?? '',
                ];
            }
        }
        if ($lam->count() > 0) {
            return response()->json(['message' => 'Retrieved Successfully', 'data' => $lam], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => $lam], 200);
        }
    }

    public function staffLeaveAndAbsence(Request $request, $staff_id = null)
    {
        if (\Request::route()->getPrefix() == 'api/admin/self-service') {
            $staff_id = Auth::user()->staff->id;
        }

        $lam = LeaveAndAbsenceManagement::where('company_id', $this->sessionDetails('company_id'))->where('staff_id', $staff_id);

        if (isset($request->type) && !empty($request->type)) {
            $lam = $lam->where('type', $request->type);
        }

        if (isset($request->status) && !empty($request->status)) {
            $lam = $lam->where('status', $request->status);
        }

        if (isset($id) && !empty($id)) {
            $lam = $lam->where('id', $id)->first();

            if (!empty($lam->staff_id)) {
                $lam->request_by = [
                    'first_name' => $lam->staff->user->first_name,
                    'middle_name' => $lam->staff->user->middle_name,
                    'last_name' => $lam->staff->user->last_name,
                    'id' => $lam->staff->id,
                ];
            }

            if ($lam) {
                return response()->json(['message' => 'Retrieved Successfully', 'data' => $lam], 200);
            } else {
                return response()->json(['message' => 'No records found', 'data' => $lam], 200);
            }
        }

        $lam = $lam->orderBy('created_at', 'DESC')->paginate(20)->withQueryString();

        foreach ($lam as $l) {
            if (!empty($l->staff_id)) {
                $l->request_by = [
                    'first_name' => $l->staff->user->first_name,
                    'middle_name' => $l->staff->user->middle_name,
                    'last_name' => $l->staff->user->last_name,
                    'id' => $l->staff->id,
                ];
            }
        }
        if ($lam->count() > 0) {
            return response()->json(['message' => 'Retrieved Successfully', 'data' => $lam], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => $lam], 200);
        }
    }

    public function getLeaveDates(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required',
            'period' => 'required',
        ]);
        $leaveDays = $request->period;
        $start = $request->start_date;
        $leaveDates = $this->leaveDateService->calculateLeaveDates($start, $leaveDays, true, []);
        return response()->json(['message' => 'Fetched Leave Dates Successfully', 'data' => $leaveDates], 200);
    }

    public function store(Request $request, $internal = null)
    {
        $validator = Validator::make($request->all(), [
            'period' => 'required',
            'reason' => 'required',
            'start_date' => 'required',
            'bypass_approval' => 'sometimes',
            'delegate_duty' => [
                'required',
                'exists:staff,id,company_id,' . $this->sessionDetails('company_id'),
                Rule::notIn([$request->staff_id ?? Auth::user()->staff->id]),
                Rule::unique('leave_and_absence_management')->where(
                    fn ($query) => $query->where('end_date', $request->end_date)
                ),
            ],
        ], [
            'delegate_duty.not_in' => 'The selected delegate cannot be yourself.',
            'delegate_duty.unique' => 'The selected delegate has already been assigned for the specified end date.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $start = Carbon::parse($request->start_date . ' 00:00:00');
        
        $staff_id = $request->staff_id ?? Auth::user()->staff->id;

        ###############################################
        ############# Generate Leave Dates ############
        ###############################################
        $leaveDays = $request->period;
        $leaveDates = $this->leaveDateService
            ->calculateLeaveDates(
                $start, 
                $leaveDays, 
                true, 
                []
            );
        
        ###############################################
        ######### Check if there is an overlap ########
        ###############################################
        $check_overlap = $this->leaveDateService->hasLeaveOverlap(
            $staff_id, 
            $leaveDates["start_date"], 
            $leaveDates["end_date"]
        );
        if ($check_overlap) {
            return response()->json([
                'error' => 'You currently have an active leave.',
                'data' => null,
            ], 400);
        }
        ################## ENDS HERE ################
        $left = $this->getLeaveAndAbsencePeriodLeft($request->staff_id ?? Auth::user()->staff->id, $request->type, true);

        if ($leaveDays > $left) {
            return response()->json([
                'error' => 'You only have ' . $left . ' days left for ' . ucfirst($request->type),
                'data' => null,
            ], 412);
        }

        if (!empty($request->bypass_approval) && $request->bypass_approval == 'Yes') {
            $status = 'approved';
        }

        $lam = LeaveAndAbsenceManagement::create([
            'staff_id' => $staff_id,
            'start_date' => $request->start_date,
            'end_date' => $leaveDates["end_date"],
            'period' => $leaveDays,
            'reason' => $request->reason,
            'type' => $request->type ?? 'leave',
            'delegate_duty' => $request->delegate_duty,
            'status' => $status ?? 'pending',
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        $user = Staff::where('id', $staff_id)->first()->user;
        $data = [
            'title' => ucfirst($request->type ?? 'leave') . " Request",
            'content' => 'Your ' . $request->type . ' request has been received and it is pending approval',
            'link' => url('/') . '/single-staff-leave-and-absence/api/admin/' . $lam->staff_id . '?type=leave',
            'user_type' => 'staff',
            'staff_id' => $lam->staff_id,
            'company_id' => $this->sessionDetails('company_id')
        ];

        $this->notify($data);
        $data['email'] = $user->email;
        $data['user'] = $user;
        $data['lam'] = $lam;
        $data['type'] = 'create.leave.request';
        $data['subject'] = 'Leave Application Acknowledgment';

        $this->logEmail($data);

        LogService::admin(auth()->user()->id, $user?->id, 'Create a leave and absence management (' . $lam->id  . ')', 'CREATE');
        return response()->json(['message' => 'Created successfully', 'data' => $lam], 200);
    }

    public function approveLeaveAndAbsence($id)
    {
        $lam = LeaveAndAbsenceManagement::find($id);

        if ($lam->status == 'approved') {
            return response()->json(['message' => 'Already Approved', 'data' => null], 200);
        }

        $left = $this->getLeaveAndAbsencePeriodLeft($lam->staff_id ?? Auth::user()->staff->id, $lam->type, true);

        if ($lam->period > $left) {
            return response()->json([
                'error' => 'You only have ' . $left . ' days left for ' . ucfirst($lam->type),
                'data' => null,
            ], 412);
        }

        $lam->update(['status' => 'approved']);
        $data = [
            'title' => 'Leave Approved',
            'content' => 'Your leave request has been approved by ' . Auth::user()->first_name . ' ' . Auth::user()->last_name,
            'link' => url('/') . '/single-staff-leave-and-absence/api/admin/' . $lam->staff_id . '?type=' . $lam->type,
            'user_type' => 'staff',
            'staff_id' => $lam->staff_id,
            'company_id' => $this->sessionDetails('company_id')
        ];

        $this->notify($data);

        $data['subject'] = 'Leave Approved';
        $data['email'] = $lam->staff->user->email;
        $data['type'] = 'approve.leave.request';

        $this->logEmail($data);

        LogService::admin(auth()->user()->id, $lam->staff->user?->id, 'Approve a leave and absence management (' . $lam->id  . ')', 'UPDATE');
        return response()->json(['message' => 'Approved successfully', 'data' => $lam], 200);
    }

    public function declineLeaveAndAbsence($id)
    {
        $lam = LeaveAndAbsenceManagement::find($id);
        $lam->update(['status' => 'declined']);

        $data = [
            'title' => ucfirst($lam->type) . ' Request Declined!',
            'content' => 'Your ' . ucfirst($lam->type) . ' has been declined by ' . Auth::user()->first_name . ' ' . Auth::user()->last_name,
            'link' => url('/') . '/api/admin/leave-and-absence/' . $lam->id,
            'user_type' => 'staff',
            'staff_id' => $lam->staff_id,
            'company_id' => $this->sessionDetails('company_id')
        ];

        $this->notify($data);

        LogService::admin(auth()->user()->id, $lam->staff->user?->id, 'Decline a leave and absence management (' . $lam->id  . ')', 'UPDATE');
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

        LogService::admin(auth()->user()->id, null, 'Update or create a leave and absence setting (' . $setting->id  . ')', 'UPDATE');
        return response()->json(['message' => 'Updated successfully', 'data' => null], 200);
    }

    public function getLeaveAndAbsenceSetting(Request $request)
    {
        $settings = LeaveAndAbsenceSetting::where('company_id', $this->sessionDetails('company_id'));

        if (!empty($request->type)) {
            $settings = $settings->where('type', $request->type)->first();

            return response()->json(['message' => 'Retrieved successfully', 'data' => $settings], 200);
        }

        return response()->json(['message' => 'Retrieved successfully', 'data' => $settings->get()], 200);
    }
}
