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
            ->join('users', 'users.id', '=', 'staff.user_id')
            ->whereNotNull('users.date_of_birth')
            ->orderBy('users.date_of_birth', 'ASC')
            ->select('staff.id AS staff_id', 'users.id AS user_id', 'staff.created_at', 'user_id', 'users.date_of_birth AS date_of_birth', 'users.first_name', 'users.last_name', 'users.avatar', 'staff.company_id')
            ->take(10)
            ->get();

        $staffsX = Staff::where('staff.company_id', $this->sessionDetails('company_id'))
            ->join('users', 'users.id', '=', 'staff.user_id')
            ->whereNotNull('staff.date_of_employment')
            ->orderBy('users.date_of_birth', 'ASC')
            ->select('staff.id AS staff_id', 'users.id AS user_id', 'user_id', 'staff.date_of_employment AS date_of_employment', 'users.first_name', 'users.last_name', 'users.avatar', 'staff.company_id')
            ->take(10)
            ->get();


        $col = Staff::where('staff.company_id', $this->sessionDetails('company_id'))
            ->join('users', 'users.id', '=', 'staff.user_id')
            ->orderBy('staff.created_at', 'ASC')
            ->select('staff.id AS staff_id', 'users.id AS user_id', 'staff.created_at', 'user_id', 'users.first_name', 'users.last_name', 'users.avatar', 'staff.company_id')
            ->take(10)
            ->get();

        $additional_details = !empty(Auth::user()->additional_details) ? json_decode(Auth::user()->additional_details, true) : [];
        $nok_details = !empty(Auth::user()->next_of_kin) ? json_decode(Auth::user()->next_of_kin, true) : [];

        $expectedProfile = [
            'first_name' => !empty(Auth::user()->first_name) ? 1 : 0,
            'middle_name' => !empty(Auth::user()->middle_name) ? 1 : 0,
            'last_name' => !empty(Auth::user()->last_name) ? 1 : 0,
            'email_address' => !empty(Auth::user()->email) ? 1 : 0,
            'phone_number' => !empty(Auth::user()->phone) ? 1 : 0,
            'alternate_email_address' => !empty($additional_details['alternate_email']) ? 1 : 0,
            'username' => !empty(Auth::user()->username) ? 1 : 0,
            'gender' => !empty(Auth::user()->gender) ? 1 : 0,
            'date_of_birth' => !empty(Auth::user()->date_of_birth) ? 1 : 0,
            'marital_status' => !empty($additional_details['marital_status']) ? 1 : 0,
            'alternate_number' => !empty($additional_details['alternate_number']) ? 1 : 0,
            'nationality' => !empty($additional_details['nationality']) ? 1 : 0,
            'state' => !empty($additional_details['state']) ? 1 : 0,
            'lga' => !empty($additional_details['lga']) ? 1 : 0,
            'residential_address' => !empty($additional_details['residential_address']) ? 1 : 0,
            'nok_first_name' => !empty($nok_details['nok_first_name']) ? 1 : 0,
            'nok_last_name' => !empty($nok_details['nok_last_name']) ? 1 : 0,
            'nok_phone_number' => !empty($nok_details['nok_phone_number']) ? 1 : 0,
            'nok_house_address' => !empty($nok_details['nok_house_address']) ? 1 : 0,
            'nok_email_address' => !empty($nok_details['nok_email_address']) ? 1 : 0,
            'nok_relationship' => !empty($nok_details['nok_relationship']) ? 1 : 0
        ];

        $profile_completenes = array_sum($expectedProfile) / count($expectedProfile) * 100;
      
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
                'avatar' => !empty($b->avatar) ? url('/') . $b->avatar : '',
                'date_of_birth' => Carbon::parse($b->date_of_birth)->format('jS \\of F'),
            ];
        }

        foreach ($staffsX as $b) {
            $work_anniversary[] = [
                'id' => $b->staff_id,
                'first_name' => $b->first_name,
                'last_name' => $b->last_name,
                'avatar' => !empty($b->avatar) ? url('/') . $b->avatar : '',
                'date_of_employment' => Carbon::parse($b->date_of_employment)->format('jS \\of F'),
            ];
        }

        $time = Carbon::parse(Auth::user()->staff->date_of_employment)->diff(Carbon::now());
        $time_in_company = $time->y.($time->y > 1 ? 'years, ':'year, ').$time->m. ($time->m > 1 ? 'months' : 'month');
        $my_anniversary = Carbon::parse(Auth::user()->staff->date_of_employment)->format('jS\\, F');

        foreach ($col as $b) {
            $colleagues[] = [
                'id' => $b->staff_id,
                'first_name' => $b->first_name,
                'last_name' => $b->last_name,
                'avatar' => !empty($b->avatar) ? $b->avatar : '',
                'created_at' => Carbon::parse($b->created_at)->format('jS \\of F'),
            ];
        }
      
        return response()->json([
            'first_name' => Auth::user()->first_name ?? '',
            'middle_name'=> Auth::user()->middle_name ?? '',
            'last_name' => Auth::user()->last_name ?? '',
            'work_id' => Auth::user()->staff_id ?? '',
            'job_role'=> Auth::user()->staff->job_role->name ?? '',
            'avatar' => !empty(Auth::user()->avatar) ? Auth::user()->avatar : '',
            'time_in_company'=>$time_in_company,
            'profile_completeness'=> (int) $profile_completenes.'%',
            'my_anniversary'=> $my_anniversary, 
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

    public function updateMyWorkProfile(Request $request){
        $request['staff_id'] = Auth::user()->staff->id;
        $s_con = new StaffController();
       
        $r = new \Illuminate\Http\Request($request->all());
        return $s_con->updateStaff($r, 'internal');
    }

    public function addAvatar(Request $request){
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }
        $avatar = $this->uploadFile($request->avatar, 'avatars',500,500);
        Auth::user()->update(['avatar'=>$avatar]);

        return response()->json(['message' => 'success', 'data' => Auth::user()->avatar], 200);
    }

    public function getAvatar(Request $request, $staff_id)
    {
        $avatar = Staff::where('id', $staff_id)->first()->user->avatar;
        return response()->json(['message' => 'success', 'data' => $avatar], 200);
    }

    public function deleteAvatar(){
        $file = DB::table('users')->where('id',Auth::user()->id)->value('avatar');
        
        $this->deleteFile($file);
        return response()->json(['message' => 'Delete succesful', 'data' => null], 200);

    }

    public function uploadDocuments(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:staff_document_settings,id,company_id,' . $this->sessionDetails('company_id'),
            'staff_id' => 'required|exists:staff,id,company_id,' . $this->sessionDetails('company_id'),
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $setting = DB::table('staff_document_settings')->where(['company_id' => $this->sessionDetails('company_id'), 'id'=>$request->id])->first();
        $count = StaffUploadedDocument::where('staff_document_id', $setting->id)->count();
       
        if ($count >= $setting->required) {
            return response()->json([
                'error' => 'You have already uploaded maximum number of required files for '.$setting->name,
                'data' => null,
            ], 422);
        }
      
        $fileX = $this->uploadProtectedFile($request->file, $this->sessionDetails('company_slug'));

        $upload = StaffUploadedDocument::create([
            'staff_id' => $request->staff_id,
            'staff_document_id' => $setting->id,
            'name' => $setting->name,
            'file' => $fileX,
            'uploaded' => $count++,
            'required' => $setting->required,
            'company_id' =>  $this->sessionDetails('company_id')
        ]);

        return response()->json(['message' => 'Upload succesful', 'data' => $upload->file], 200);

    }

    public function sendResetPasswordEmail(Request $request){
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
        $data['token'] = base64_encode((mt_rand(100000000000, 999999999999)));
        $data['email'] = Auth::user()->email;
        
        // Create a new code
        $codeData = ResetCodePassword::create($data);
        $data['type'] = 'password-reset';
        // Log email only
        $mes = new MessageTransactionController();
        $mes->logEmailTransaction($data);

        return response()->json(['message' => 'Password reset link has been sent to registered email', 'data' => null], 200);
    }

}
