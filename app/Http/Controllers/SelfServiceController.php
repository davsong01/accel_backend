<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Staff;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                'avatar' => !empty($b->avatar) ? url('/') . $b->avatar : '',
                'created_at' => Carbon::parse($b->created_at)->format('jS \\of F'),
            ];
        }
      
        return response()->json([
            'first_name' => Auth::user()->first_name ?? '',
            'middle_name'=> Auth::user()->middle_name ?? '',
            'last_name' => Auth::user()->last_name ?? '',
            'work_id' => Auth::user()->staff_id ?? '',
            'job_role'=> Auth::user()->staff->job_role->name ?? '',
            'avatar' => !empty(Auth::user()->avatar) ? url('/') . Auth::user()->avatar : '',
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
        $staff = Staff::with('roles')->where(['staff.id' => Auth::user()->staff->id])
            ->leftjoin("users", "staff.user_id", "=", "users.id")->where(['users.status' => 'active', 'type' => 'staff'])
            ->leftjoin("job_types", "staff.jobtype_id", "=", "job_types.id")
            ->leftjoin("job_roles", "staff.jobrole_id", "=", "job_roles.id")
            ->leftjoin("branches", "users.branch_id", "=", "branches.id")
            ->leftjoin("departments", "staff.department_id", "=", "departments.id")
            ->select("staff.id AS staff_id", "users.status", "staff.created_at", "users.first_name", "users.username", "users.last_name", "users.email", "staff.id AS staff_id", "users.type", "users.company_id", "staff.jobtype_id", "users.id AS user_id", "staff.date_of_employment", "branches.id AS branch_id", "branches.name AS branch_name", "departments.id AS department_id", "departments.name AS department_name", "job_roles.id AS job_role_id", "job_roles.name AS job_role_name", "job_types.id AS job_type_id", "job_types.name AS job_type_name", "users.residential_address AS staff_residential_address", "users.additional_details", "users.next_of_kin", "users.date_of_birth AS date_of_birth")
            ->first();

        $additional_details = !empty($staff->additional_details) ? json_decode($staff->additional_details) : collect([]);
        $next_of_kin = !empty($staff->next_of_kin) ? json_decode($staff->next_of_kin) : collect([]);
        $time = Carbon::parse($staff->date_of_employment)->diff(Carbon::now());
        $time_in_company = $time->y . ($time->y > 1 ? 'years, ' : 'year, ') . $time->m . ($time->m > 1 ? 'months' : 'month');
        $my_anniversary = Carbon::parse($staff->date_of_employment)->format('jS\\, F');

        $data['job_information'] = [
            'job_type' => [
                'id' => $staff->job_type_id ?? null,
                'name' => $staff->job_type_name ?? null,
            ],
            'employment_date' => $staff->date_of_employment ?? null,
            'time_in_company' => $time_in_company,
            'work_anniversary' => $my_anniversary,
            'department' => [
                'id' => $staff->department_id ?? null,
                'name' => $staff->department_name ?? null
            ],
            'job_role' => [
                'id' => $staff->job_role_id ?? null,
                'name' => $staff->job_role_name ?? null,
            ],
            'role' => [
                'id' => $staff->role->id ?? null,
                'name' => $staff->role->name ?? null,
            ],
            'branch' => [
                'id' => $staff->branch_id ?? null,
                'name' => $staff->branch_name ?? null,
            ]
        ];

        $data['personal_information'] = [
            'first_name' => $staff->first_name ?? null,
            'middle_name' => $staff->middle_name ?? null,
            'last_name' => $staff->last_name ?? null,
            'username' => $staff->user->username ?? null,
            'gender' => $staff->user->gender ?? null,
            'marital_status' => $additional_details->marital_status ?? null,
            'dob' => $staff->date_of_birth,
        ];

        $data['contact_and_additional_information'] = [
            'residential_address' => $staff->staff_residential_address ?? null,
            'address' => $staff->email ?? null,
            'alternate_email' => $additional_details->alternate_email ?? null,
            'state' => !empty($additional_details->state) ? $this->getStateDetails($additional_details->state) : null,
            'lga' => !empty($additional_details->lga) ? $this->getLgaDetails($additional_details->lga) : null,
            'nationality' => !empty($additional_details->nationality) ? $this->getCountryDetails($additional_details->nationality) : null,
        ];

        $data['next_of_kin_information'] = [
            'first_name' => $next_of_kin->nok_first_name ?? null,
            'last_name' => $next_of_kin->nok_last_name ?? null,
            'house_address' => $next_of_kin->nok_house_address ?? null,
            'email_address' => $next_of_kin->nok_email_address ?? null,
            'phone_number' => $next_of_kin->nok_phone_number ?? null,
            'relationship' => $next_of_kin->nok_relationship ?? null,
        ];

        return response()->json(['message' => 'success', 'data' => $data], 200);
    }
}
