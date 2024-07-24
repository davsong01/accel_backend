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
use App\Events\LeaveCreated;
use App\Events\LeaveStatusUpdated;
use App\Http\Responses\CustomResponse;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use DB;

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
            $lam = $lam->whereBetween('start_date', [$startDate, $endDate]);
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
                return CustomResponse::success(
                    'Retrieved successfully', $lam->toArray(), 200
                );
            } else {
                return CustomResponse::error(
                    'No record found!', 404, $lam->toArray()
                );
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
            return CustomResponse::success(
                'Retrieved successfully', $lam->toArray(), 200
            );
        } else {
            return CustomResponse::error(
                'No record found!', 404, $lam->toArray()
            );
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
                return CustomResponse::success(
                    'Retrieved successfully', $lam->toArray(), 200
                );
            } else {
                return CustomResponse::error(
                    'No record found!', 404, $lam->toArray()
                );
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
            return CustomResponse::success(
                'Retrieved successfully', $lam->toArray(), 200
            );
        } else {
            return CustomResponse::error(
                'No record found!', 404, $lam->toArray()
            );
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
        
        return CustomResponse::success(
            'Fetched Leave Dates Successfully', $leaveDates, 200
        );
    }

    public function store(Request $request, $internal = null)
    {
        $validator = Validator::make($request->all(), [
            'period' => 'required',
            'reason' => 'required',
            'start_date' => 'required',
            'bypass_approval' => 'sometimes',
            'file_path' => "required|mimes:docx,doc,pdf|max:5120",
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
            return CustomResponse::error(
                $validator->messages()->first(), 412, []
            );
        }

        DB::beginTransaction();

        try{
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
                return CustomResponse::error(
                    'You currently have an active leave.', 400, []
                );
            }
            ################## ENDS HERE ################
            $left = $this->getLeaveAndAbsencePeriodLeft($request->staff_id ?? Auth::user()->staff->id, $request->type, true);

            if ($leaveDays > $left) {
                return CustomResponse::error(
                    'You only have ' . $left . ' days left for ' . ucfirst($request->type), 412, []
                );
                
            }

            if (!empty($request->bypass_approval) && $request->bypass_approval == 'Yes') {
                $status = 'approved';
            }

            // check if file exist
            $filepath = "";
            if($request->has("file_path")){
                $filepath = $this->uploadProtectedFile($request->file_path, 'leave_application_documents')['filepath'];
            }
            $lam = LeaveAndAbsenceManagement::create([
                'staff_id' => $staff_id,
                'start_date' => $leaveDates["start_date"],
                'end_date' => $leaveDates["end_date"],
                'resumption_date' => $leaveDates["resumption_date"],
                'period' => $leaveDays,
                'reason' => $request->reason,
                'type' => $request->type ?? 'leave',
                'delegate_duty' => $request->delegate_duty,
                'status' => $status ?? 'pending',
                'company_id' => $this->sessionDetails('company_id'),
                'file_path' =>  $filepath
            ]);

            $user = $lam->staff->user;
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

            // Check if Relief Officer is selected then Notify Relief Officer
            if(isset($request->delegate_duty)){
                $officer = $lam->reliefOfficer->user;

                $user_name = $user->first_name . " - " . $user->last_name;

                // Dispatch event after leave is created
                event(new LeaveCreated($lam));
            }

            $this->logEmail($data);

            $staff = Staff::find($request->input('staff_id', Auth::user()->staff->id));
            $company_id = $this->sessionDetails('company_id');
            $users = User::with('staff:id,user_id')->where('staff_id', '!=', $staff->id)
            ->where(['type' => 'staff', 'company_id' => $company_id, 'status' => 'active'])->whereNotNull('staff_id')
            ->get();
    
            foreach ($users as $user) {
                $content = "<strong>{$user->fullname}</strong><br><br>";
                $content .= "<div><p style=\"text-align:justify !important\">" . auth()->user()->email . " has requested for an/a ". ($request->type ?? 'leave') .", kindly log into your account to approve or decline the request.</p><br><br></div>";
    
                $admin_mail = [
                    'subject' => ucfirst($request->type ?? 'Leave') . ' Application Request',
                    'email' => $user->email,
                    'content' =>  $content,
                    'company_id' => $company_id,
                    'user' => $user,
                    'type' => 'send.email.to.admin.for.create.leave-absence',
                    'name' => $user->fullname,
        
                ];
        
                $this->logEmail($admin_mail);
            }

            LogService::admin(auth()->user()->id, $user?->id, 'Create a leave and absence management (' . $lam->id  . ')', 'CREATE');

            // Commit the transaction
            DB::commit();

            
            return CustomResponse::success(
                'Created Successfully', $lam->toArray(), 200
            );
        } catch(Exception $e){
            // Rollback the transaction
            DB::rollBack();

            return CustomResponse::error(
                "Something happened! " . $e->getMessage(), 500, []
            );
        }
    }

    public function approveLeaveAndAbsence($id)
    {
        $lam = LeaveAndAbsenceManagement::find($id);
        if ($lam) {
            if ($lam->status == 'approved') {
                return CustomResponse::success(
                    'Alread Approved', [], 200
                );
            }

            $left = $this->getLeaveAndAbsencePeriodLeft($lam->staff_id ?? Auth::user()->staff->id, $lam->type, true);

            if ($lam->period > $left) {
                return CustomResponse::error(
                    'You only have ' . $left . ' days left for ' . ucfirst($lam->type), 412, []
                );
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

            // Dispatch LeaveStatusUpdated event
            // event(new LeaveStatusUpdated($lam, 'approved'));
            Event::dispatch(new LeaveStatusUpdated($lam, 'approved'));
            $this->logEmail($data);

            LogService::admin(auth()->user()->id, $lam->staff->user?->id, 'Approve a leave and absence management (' . $lam->id  . ')', 'UPDATE');

            return CustomResponse::success(
                'Approved successfully', $lam->toArray(), 200
            );
        } else {
            return CustomResponse::error(
                'Failed to approve. Leave not found!', 400, []
            );
        }
    }

    public function declineLeaveAndAbsence($id)
    {
        $lam = LeaveAndAbsenceManagement::find($id);
        if ($lam) {
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
            // Dispatch LeaveStatusUpdated event
            //event(new LeaveStatusUpdated($lam, 'rejected'));
            Event::dispatch(new LeaveStatusUpdated($lam, 'rejected'));

            LogService::admin(auth()->user()->id, $lam->staff->user?->id, 'Decline a leave and absence management (' . $lam->id  . ')', 'UPDATE');
            return CustomResponse::success(
                'Declined successfully', $lam->toArray(), 200
            );
        } else {
            return CustomResponse::error(
                'Failed to decline. Leave not found!', 400, []
            );
        }
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
            return CustomResponse::error(
                $validator->messages()->first(), 412, []
            );
        }

        $company_id = $this->sessionDetails('company_id');

        $setting = LeaveAndAbsenceSetting::updateOrCreate(['company_id' => $company_id, 'type' => $request->type], [
            'type' => $request->type,
            'period' => $request->period
        ]);

        LogService::admin(auth()->user()->id, null, 'Update or create a leave and absence setting (' . $setting->id  . ')', 'UPDATE');
        
        return CustomResponse::success(
            'Updated successfully', [], 200
        );
    }

    public function getLeaveAndAbsenceSetting(Request $request)
    {
        $settings = LeaveAndAbsenceSetting::where('company_id', $this->sessionDetails('company_id'));

        if (!empty($request->type)) {
            $settings = $settings->where('type', $request->type)->first();

            return CustomResponse::success(
                'Retrieved successfully', $settings->toArray(), 200
            );
        }

        return CustomResponse::success(
            'Retrieved successfully', $settings->get(), 200
        );
    }

    public function employeesOnLeave(Request $request, $type)
    {

        switch ($type) {
            case 'today':
                $employeesOnLeave = $this->leaveDateService->getEmployeesOnLeaveToday();
                break;
            case 'week':
                $employeesOnLeave = $this->leaveDateService->getEmployeesOnLeaveThisWeek();
                break;
            case 'month':
                $employeesOnLeave = $this->leaveDateService->getEmployeesOnLeaveThisMonth();
                break;
            case 'year':
                $employeesOnLeave = $this->leaveDateService->getEmployeesOnLeaveThisYear();
                break;
            default:
                $employeesOnLeave = [];
                break;
        }

        return CustomResponse::success(
            'Retrieved successfully', $employeesOnLeave, 200
        );
    }
}
