<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Staff;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\StaffDocument;
use App\Models\ResetCodePassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\StaffUploadedDocument;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\MessageTransactionController;

class SelfServiceController extends Controller
{
    public function dashboard()
    {
        $staffs = Staff::where('staff.company_id', $this->sessionDetails('company_id'))
            ->where('staff.id', '!=', Auth::user()->staff->id)
            ->join('users', 'users.id', '=', 'staff.user_id')
            ->whereNotNull('users.date_of_birth')
            ->orderBy('users.date_of_birth', 'ASC')
            ->select('staff.id AS staff_id', 'users.id AS user_id', 'staff.created_at', 'user_id', 'users.date_of_birth AS date_of_birth', 'users.first_name', 'users.last_name', 'users.avatar', 'staff.company_id')
            ->take(10)
            ->get();

        $staffsX = Staff::where('staff.company_id', $this->sessionDetails('company_id'))
            ->where('staff.id', '!=', Auth::user()->staff->id)
            ->join('users', 'users.id', '=', 'staff.user_id')
            ->whereNotNull('staff.date_of_employment')
            ->orderBy('users.date_of_birth', 'ASC')
            ->select('staff.id AS staff_id', 'users.id AS user_id', 'user_id', 'staff.date_of_employment AS date_of_employment', 'users.first_name', 'users.last_name', 'users.avatar', 'staff.company_id')
            ->take(10)
            ->get();


        $col = Staff::where('staff.company_id', $this->sessionDetails('company_id'))
            ->where('staff.id', '!=', Auth::user()->staff->id)
            ->join('users', 'users.id', '=', 'staff.user_id')
            ->orderBy('staff.created_at', 'ASC')
            ->select('staff.id AS staff_id', 'users.id AS user_id', 'staff.created_at', 'user_id', 'users.first_name', 'users.last_name', 'users.avatar', 'staff.company_id')
            ->take(10)
            ->get();

        $additional_details = !empty(Auth::user()->additional_details) ? json_decode(Auth::user()->additional_details, true) : [];
        $nok_details = !empty(Auth::user()->next_of_kin) ? json_decode(Auth::user()->next_of_kin, true) : [];

        $profile_completenes = $this->profileCompletenes();

        // $payments = Payment::where('staff_id', Auth::user()->staff->id)->get();
        // dd($payments);
        $birthdays = [];
        $work_anniversary = [];
        $colleagues = [];

        foreach ($staffs as $b) {
            $birthdays[] = [
                'id' => $b->user_id,
                'first_name' => $b->first_name,
                'last_name' => $b->last_name,
                'avatar' => !empty($b->avatar) ? $this->encodeUploadedFile($b->avatar) : '',
                'date_of_birth' => Carbon::parse($b->date_of_birth)->format('jS \\of F'),
            ];
        }

        foreach ($staffsX as $b) {
            $work_anniversary[] = [
                'id' => $b->staff_id,
                'first_name' => $b->first_name,
                'last_name' => $b->last_name,
                'avatar' => !empty($b->avatar) ? $this->encodeUploadedFile($b->avatar) : '',
                'date_of_employment' => Carbon::parse($b->date_of_employment)->format('jS \\of F'),
            ];
        }

        $time = Carbon::parse(Auth::user()->staff->date_of_employment)->diff(Carbon::now());
        $time_in_company = ($time->y > 0 ? $time->y . ($time->y > 1 ? 'years, ' : 'year, ') : '') .  ($time->m > 0 ? ($time->m > 1 ? $time->m . ' months' : $time->m . ' month') : 'Less than a month');
        $my_anniversary = Carbon::parse(Auth::user()->staff->date_of_employment)->format('jS\\, F');

        foreach ($col as $b) {
            $colleagues[] = [
                'id' => $b->staff_id,
                'user_id' => $b->user_id,
                'first_name' => $b->first_name,
                'last_name' => $b->last_name,
                'avatar' => !empty($b->avatar) ? $this->encodeUploadedFile($b->avatar) : '',
                'created_at' => Carbon::parse($b->created_at)->format('jS \\of F'),
            ];
        }

        return response()->json([
            'user_id' => Auth::user()->id ?? '',
            'first_name' => Auth::user()->first_name ?? '',
            'middle_name' => Auth::user()->middle_name ?? '',
            'last_name' => Auth::user()->last_name ?? '',
            'work_id' => Auth::user()->staff_id ?? '',
            'job_role' => Auth::user()->staff->job_role->name ?? '',
            'avatar' => !empty(Auth::user()->avatar) ? Auth::user()->avatar : '',
            'time_in_company' => $time_in_company,
            'profile_completeness' => (int) $profile_completenes . '%',
            'my_anniversary' => $my_anniversary,
            'birthdays' => $birthdays,
            'work_anniversary' => $work_anniversary,
            'colleagues' => $colleagues
        ], 200);
    }

    public function myWorkProfile()
    {
        $sController = new StaffController();
        return $sController->getSingleStaffProfile(Auth::user()->staff->id, 'internal');
    }

    public function updateMyWorkProfile(Request $request)
    {
        $request['staff_id'] = Auth::user()->staff->id;
        $s_con = new StaffController();

        $r = new \Illuminate\Http\Request($request->all());
        return $s_con->updateStaff($r, 'internal');
    }

    public function addAvatar(Request $request, $staff_id = null)
    {
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $avatar = $this->uploadFile($request->avatar, 'avatars', 500, 500);

        if (isset($staff_id)) {
            $staff = Staff::where('id', $staff_id)->first();
            $user = $staff->user;
        } else {
            $user = Auth::user();
        }

        $user->update(['avatar' => $avatar]);

        return response()->json(['message' => 'success', 'data' => Auth::user()->avatar], 200);
    }

    public function getAvatar(Request $request, $staff_id)
    {
        $avatar = Staff::where('id', $staff_id)->first()->user->avatar;
        return response()->json(['message' => 'success', 'data' => $avatar], 200);
    }

    public function deleteAvatar($staff_id = null)
    {
        if (isset($staff_id)) {
            $staff = Staff::where('id', $staff_id)->first();
            $user = $staff->user;
        } else {
            $user = Auth::user();
        }
        // $user = User::where('id',Auth::user()->id);
        if (!empty($user->avatar)) {
            $this->deleteFile($user->avatar);
        }

        $user->update(['avatar' => null]);

        return response()->json(['message' => 'Delete succesful', 'data' => null], 200);
    }

    public function uploadDocuments(Request $request, $self = true)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:staff_document_settings,id,company_id,' . $this->sessionDetails('company_id'),
            'staff_id' => 'required|exists:staff,id,company_id,' . $this->sessionDetails('company_id'),
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            if ($self == false) {
                return [
                    'error' => $validator->messages()->first(),
                    'data' => null,
                ];
            }
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $staff_id = $self == true ? Auth::user()->staff->id : $request->staff_id;

        $setting = DB::table('staff_document_settings')->where(['company_id' => $this->sessionDetails('company_id'), 'id' => $request->id])->first();
        $count = StaffUploadedDocument::where(['staff_document_id' => $setting->id, 'staff_id' => $staff_id])->count();

        if ($count >= $setting->required) {
            if ($self == false) {
                return [
                    'code' => 422,
                    'error' => 'You have already uploaded maximum number of required files for ' . $setting->name,
                    'data' => null,
                ];
            }
            return response()->json([
                'error' => 'You have already uploaded maximum number of required files for ' . $setting->name,
                'data' => null,
            ], 422);
        }

        $fileX = $this->uploadProtectedFile($request->file, $this->sessionDetails('company_slug'));

        $upload = StaffUploadedDocument::create([
            'staff_id' => $staff_id,
            'staff_document_id' => $setting->id,
            'name' => $setting->name,
            'file' => $fileX,
            'uploaded' => $count++,
            'required' => $setting->required,
            'company_id' => $this->sessionDetails('company_id')
        ]);

        if ($self == false) {
            return [
                'code' => 200,
                'message' => 'Upload succesful',
                'data' => $upload->file
            ];
        }
        return response()->json(['message' => 'Upload succesful', 'data' => $upload->file], 200);
    }

    public function sendResetPasswordEmail(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        // check if password for this user maches incoming
        if (!Hash::check($request->password, Auth::user()->password)) {
            return response()->json(['error' => 'Current Password supplied is incorrect', 'data' => null], 422);
        }
        // Delete all old code that user send before.
        ResetCodePassword::where('email', Auth::user()->email)->delete();
        // Generate random code
        $rand = mt_rand(100000000000, 999999999999);
        $data['token'] = base64_encode($rand);
        $data['email'] = Auth::user()->email;

        // Create a new code
        $codeData = ResetCodePassword::create($data);
        $data['type'] = 'password-reset';
        $data['token'] = $rand;

        // Log email only
        $mes = new MessageTransactionController();
        $mes->logEmailTransaction($data);

        return response()->json(['message' => 'Password reset link has been sent to registered email', 'data' => null], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $encodeToken = base64_encode($request->token);
        $passwordReset = ResetCodePassword::firstWhere('token', $encodeToken);
        if (!$passwordReset) {
            return response(['error' => 'Invalid Token', 'data' => null], 422);
        }

        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response(['error' => 'Token has expired', 'data' => null], 422);
        }
        // find user's email 
        $user = User::firstWhere('email', $passwordReset->email);
        $password = Hash::make($request->password);
        // update user password
        $user->update(['password' => $password]);

        // delete current token 

        $passwordReset->delete();

        return response(['message' => 'Password successfully reset', 'data' => null], 200);
    }

    public function fetchUnreadNotifications(Request $request, $staff_id = null)
    {
        $staff_id = $staff_id ?? Auth::user()->staff->id;
        $staff = Staff::where('id', $staff_id)->first();
        $user = User::find($staff->user->id);

        $unread = Auth::user()->unreadnotifications()->where('company_id', $this->sessionDetails('company_id'))->paginate(20)->withQueryString();

        foreach ($unread as $notification) {
            $notification['id'] = $notification->id;
            $notification['title'] = $notification->data['title'];
            $notification['content'] = $notification->data['content'];
            $notification['link'] = $notification->data['link'];
            $notification['date'] = $notification->created_at->diffForHumans();

            unset($notification['type']);
            unset($notification['company_id']);
            unset($notification['user_type']);
            unset($notification['notifiable_type']);
            unset($notification['notifiable']);
            unset($notification['data']);
            unset($notification['notifiable_id']);
            unset($notification['read_at']);
            unset($notification['created_at']);
            unset($notification['updated_at']);
        }

        return response(['message' => 'Successfully retrievd', 'data' => $unread], 200);
    }

    public function fetchViewedNotifications(Request $request)
    {
        // $staff = Staff::where('id', $staff_id)->first();
        // $user = User::find($staff->user->id);
        $user = Auth::user();
        $notifications = [];
        $read = $user->readNotifications()->where('company_id', $this->sessionDetails('company_id'))->paginate(20)->withQueryString();

        foreach ($read as $notification) {
            $notification['id'] = $notification->id;
            $notification['title'] = $notification->data['title'];
            $notification['content'] = $notification->data['content'];
            $notification['link'] = $notification->data['link'];
            $notification['date'] = $notification->created_at->diffForHumans();

            unset($notification['type']);
            unset($notification['company_id']);
            unset($notification['user_type']);
            unset($notification['notifiable_type']);
            unset($notification['notifiable']);
            unset($notification['data']);
            unset($notification['notifiable_id']);
            unset($notification['read_at']);
            unset($notification['created_at']);
            unset($notification['updated_at']);
        }


        return response(['message' => 'Successfully retrievd', 'data' => $read], 200);
    }


    public function markNotificationsAsRead($notification_id = null)
    {
        // $staff = Staff::where('id', $staff_id)->first();
        // $user = User::find($staff->user->id);
        $user = Auth::user();
        if ($notification_id) {
            $notification = $user->unreadNotifications
                ->where('id', $notification_id)
                ->first();

            $notification->markAsRead();
        } else {
            $userUnreadNotification = $user->unreadNotifications->where('company_id', $this->sessionDetails('company_id'));

            foreach ($userUnreadNotification as $notification) {
                $notification->markAsRead();
            }
        }

        return response(['message' => 'Successfully read', 'data' => null], 200);
    }

    public function viewSingleNotification($notification_id)
    {
        $notification = Auth::user()->notifications
            ->where('id', $notification_id)
            ->first();

        $data = [
            'id' => $notification->id,
            'title' => $notification->data['title'],
            'content' => $notification->data['content'],
            'link' => $notification->data['link'],
            'date' => $notification->created_at->diffForHumans(),
        ];
        return response(['message' => 'Successfully retrieved', 'data' => $data], 200);
    }

    public function fetchAllNotifications()
    {
        $notifications = Auth::user()->notifications()->paginate(20)->withQueryString();
        foreach ($notifications as $notification) {
            $notification['title'] = $notification->data['title'];
            $notification['title'] = $notification->data['title'];
            $notification['content'] = $notification->data['content'];
            $notification['link'] = $notification->data['link'];
            $notification['date'] = $notification->created_at->diffForHumans();

            unset($notification['type']);
            unset($notification['company_id']);
            unset($notification['user_type']);
            unset($notification['notifiable_type']);
            unset($notification['notifiable']);
            unset($notification['data']);
            unset($notification['notifiable_id']);
            unset($notification['read_at']);
            unset($notification['created_at']);
            unset($notification['updated_at']);
        }

        return response(['message' => 'Successfully retrieved', 'data' => $notifications], 200);
    }
}
