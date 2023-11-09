<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Staff;
use App\Models\StaffRole;
use App\Imports\StaffImport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    public function getActiveStaffInRole($staff_id)
    {
        $staff = StaffRole::where("role_id", $staff_id)
            ->join("staff", "staff_roles.staff_id", "=", "staff.id")
            ->where('staff.company_id', $this->sessionDetails('company_id'))
            ->join("users", "staff.user_id", "=", "users.id")->where(['users.status' => 'active', 'type' => 'staff'])
            ->select("staff.id AS staff_id", "users.id As user_id", "users.status", "staff.created_at", "users.first_name", "users.last_name", "users.email", "users.staff_id AS user_staff_id", "staff.id AS staff_id", "users.type", "users.company_id")
            ->orderBy("staff_roles.staff_id", "desc")
            ->groupBy("staff.id")
            ->get();

        return response()->json(['message' => 'success', 'data' => $staff], 200);
    }

    public function getAllStaff(Request $request)
    {
        $staff = Staff::where(['staff.company_id' => $this->sessionDetails('company_id')])
            ->join("users", "staff.user_id", "=", "users.id")
            ->where(['type' => 'staff'])
            ->join("departments", "staff.department_id", "=", "departments.id")
            ->leftjoin("job_roles", "staff.jobrole_id", "=", "job_roles.id")
            ->leftjoin("job_types", "staff.jobtype_id", "=", "job_types.id")
            ->select("staff.id AS staff_id", "users.id As user_id", "users.status", "staff.created_at", "users.first_name", "users.middle_name", "users.last_name", "users.email", "users.type", "users.company_id", "job_roles.name AS job_role", "job_types.name AS job_type", "departments.name AS department", "users.type", "users.phone_number", "users.gender", "staff.jobtype_id AS jobtype_id", "staff.jobrole_id AS jobrole_id", "staff.department_id AS department_id")->orderBy("staff.created_at", "desc");

        if (!empty($request->name)) {
            $staff = $staff->where('first_name', 'LIKE', "%{$request->name}%")
                ->orWhere(function ($staff) use ($request) {
                    $staff->where('last_name', 'LIKE', "%{$request->name}%")
                        ->orWhere('middle_name', 'LIKE', "%{$request->name}%");
                });
        }

        if (!empty($request->status)) {
            $staff = $staff->where('users.status', $request->status);
        }

        if (!empty($request->job_type)) {
            $staff = $staff->where('staff.jobtype_id', $request->job_type);
        }

        if ($request->has('job_role') && !is_null($request->job_role)) {
            $staff = $staff->where('staff.jobrole_id', $request->job_role);
        }

        if ($request->has('department') && !is_null($request->department)) {
            $staff = $staff->where('staff.department_id', $request->department);
        }

        if (!empty($request->paginate) && $request->paginate == 'false') {
            $staff = $staff->get();
        } else {
            $staff = $staff->paginate(20)->withQueryString();
        }

        return response()->json(['message' => 'success', 'data' => $staff], 200);
    }

    public function isNotOnLeave($staff_id = null)
    {
        $staff_id = !empty($staff_id) ? $staff_id : Auth::user()->staff->id;

        $staff = Staff::with('current_approved_leave', 'user:id,first_name,middle_name,last_name')->where(['staff.company_id' => $this->sessionDetails('company_id')])->where('id', '!=', $staff_id)->get();

        $staff = $staff->filter(function ($q) {
            if (count($q->current_approved_leave) < 1) {
                return $q;
            }
        });

        return response()->json(['message' => 'Successfully retrieved', 'data' => $staff], 200);
    }

    public function getActiveStaff()
    {
        $staff = Staff::where(['staff.company_id' => $this->sessionDetails('company_id')])
            ->join("users", "staff.user_id", "=", "users.id")->where(['users.status' => 'active', 'type' => 'staff'])
            ->select("staff.id AS staff_id", "users.id As user_id", "users.status", "staff.created_at", "users.first_name", "users.last_name", "users.email", "users.staff_id AS user_staff_id", "staff.id AS staff_id", "users.type", "users.company_id")
            ->get();
        return response()->json(['message' => 'success', 'data' => $staff], 200);
    }

    public function getMultipleRoleAdmins(Request $request)
    {
        // $departments = AdminDepartment::where(['status' => 'active', 'domain_id' => \Request::get('domain_id')])->orderBy('id', 'DESC')->get();

        // foreach ($departments as $department) {
        //     $departmentRoles = Role::where(['type' => 'staff', 'admin_department_id' => $department->id, 'status' => 'active',])->get()->pluck('id');

        //     $department['staff'] = StaffRole::whereIn("role_id", $departmentRoles)
        //         ->join("staff", "staff_roles.staff_id", "=", "staff.id")->where(['staff.status' => 'active'])
        //         ->join("users", "staff.user_id", "=", "users.id")
        //         ->select("staff.id", "staff.status", "staff.type", "staff.created_at", "users.name")
        //         ->orderBy("staff.created_at", "desc")
        //         ->groupBy("staff.id")
        //         ->get();
        // }

        // return response()->json(['message' => 'successfull', 'data' => $departments], 200);
    }

    public function assignRoleToStaff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'staff_id' => 'required|exists:staff,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $assign = StaffRole::updateOrCreate([
            'role_id' => $request->role_id,
            'staff_id' => $request->staff_id,
            'company_id' => $this->sessionDetails('company_id'),
            'branch_id' => $this->sessionDetails('branch_id'),
        ]);

        return response()->json(['message' => 'Created successfully', 'data' => $assign], 200);
    }

    public function storeStaff(Request $request)
    {
        if (!isset($request->staff_id) && empty($request->staff_id)) {
            $validator = Validator::make($request->all(), [
                'email' => [
                    'required', 'email', Rule::unique('users')->where(function ($query) {
                        $query->where('company_id', $this->sessionDetails('company_id'));
                    })
                ],
                'first_name' => 'required',
                'middle_name' => 'nullable',
                'last_name' => 'required',
                'username' => 'nullable',
                'phone_number' => 'required',
                'gender' => 'required',
                'branch_id' => 'nullable',
                'jobtype_id' => 'required|exists:job_types,id,company_id,' . $this->sessionDetails('company_id'),
                'jobrole_id' => 'required|exists:job_roles,id,company_id,' . $this->sessionDetails('company_id'),
                'commission_id' => 'nullable|exists:commissions,id,company_id,' . $this->sessionDetails('company_id'),
                'date_of_employment' => 'nullable',
                'department_id' => 'required|exists:departments,id,company_id,' . $this->sessionDetails('company_id'),
                'nationality' => 'sometimes|integer',
                'state' => 'sometimes|integer',
                'lga' => 'sometimes|integer'
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $data = $validator->valid();
        $data['additional_details'] = json_encode([
            'alternate_number' => $data['alternate_number'] ?? '',
            'alternate_email' => $data['alternate_email'] ?? '',
            'state' => $data['state'] ?? '',
            'residential_address' => $data['residential_address'] ?? '',
            'marital_status' => $data['marital_status'] ?? '',
            'nationality' => $data['nationality'] ?? '',
            'lga' => $data['lga'] ?? '',
        ]);

        $data['next_of_kin'] = json_encode([
            'nok_first_name' => $data['nok_first_name'] ?? '',
            'nok_last_name' => $data['nok_last_name'] ?? '',
            'nok_phone_number' => $data['nok_phone_number'] ?? '',
            'nok_house_address' => $data['nok_house_address'] ?? '',
            'nok_email_address' => $data['nok_email_address'] ?? '',
            'nok_relationship' => $data['nok_relationship'] ?? '',
        ]);

        // Create user
       
        if (!$request->staff_id && empty($request->staff_id)) {
            $user = User::create([
                'email' => $data['email'],
                'first_name' => $data['first_name'],
                'type' => 'staff',
                'password' => Hash::make('123456789'),
                'middle_name' => $data['middle_name'] ?? '',
                'last_name' => $data['last_name'] ?? '',
                'username' => $data['username'] ?? '',
                'phone_number' => $data['phone_number'] ?? '',
                'gender' => $data['gender'] ?? '',
                'additional_details' => $data['additional_details'],
                'next_of_kin' => $data['next_of_kin'],
                'branch_id' => $data['branch_id'] ?? '',
                'date_of_birth' => $request->date_of_birth ?? null,
                'company_id' => $this->sessionDetails('company_id'),
                'staff_id' => $this->generateStaffId($data['date_of_employment'], true),
            ]);

            // Create staff
            $staff = Staff::create([
                'jobtype_id' => $data['jobtype_id'] ?? '',
                'date_of_employment' => $data['date_of_employment'] ?? '',
                'user_id' => $user->id ?? '',
                'department_id' => $data['department_id'] ?? '',
                'jobrole_id' => $data['jobrole_id'] ?? '',
                'commission_id' => $data['commission_id'] ?? '',
                'company_id' => $this->sessionDetails('company_id'),
            ]);

            $data['type'] = 'staff.store';
            $data['medium'] = 'email';
            $data['user'] = $user;
            $data['name'] = $data['name'] ?? Auth::user()->first_name;
            $data['recipient'] = $user->first_name;
            $this->logEmail($data);
        } else {
            $staff = Staff::where('id', $request->staff_id)->where('company_id', $this->sessionDetails('company_id'));
            $user = $staff->user();
        }

        return response()->json(['message' => 'Successfully created!', 'data' => ['staff_id' => $staff->id]], 200);
    }

    public function updateStaff(Request $request, $internal = null)
    {
        $staff = Staff::with('roles')->where(['staff.id' => $request->staff_id])
            ->join("users", "staff.user_id", "=", "users.id")->where(['users.status' => 'active', 'type' => 'staff'])
            ->leftjoin("job_types", "staff.jobtype_id", "=", "job_types.id")
            ->leftjoin("job_roles", "staff.jobrole_id", "=", "job_roles.id")
            ->leftjoin("branches", "users.branch_id", "=", "branches.id")
            ->leftjoin("departments", "staff.department_id", "=", "departments.id")
            ->select("users.status", "staff.created_at", "users.first_name", "users.username", "users.last_name", "users.email", "staff.id AS staff_id", "users.type", "users.company_id", "staff.jobtype_id", "users.id AS user_id", "staff.date_of_employment", "branches.id AS branch_id", "branches.name AS branch_name", "departments.id AS department_id", "departments.name AS department_name", "job_roles.id AS job_role_id", "job_roles.name AS job_role_name", "job_types.id AS job_type_id", "job_types.name AS job_type_name", "users.additional_details", "users.next_of_kin", "users.date_of_birth")
            ->first();

        if (!$staff) {
            return response()->json(['message' => 'Staff not found!', 'data' => null], 200);
        }

        $validator = Validator::make($request->all(), [
            'email' => [
                'required', Rule::unique('users')->where(function ($query) use ($staff) {
                    $query->where('company_id', $this->sessionDetails('company_id'))->where('id', '!=', (int) $staff->user_id);
                })
            ],
            'first_name' => 'nullable',
            'middle_name' => 'nullable',
            'last_name' => 'nullable',
            'username' => 'nullable',
            'phone_number' => 'nullable',
            'gender' => 'nullable',
            'branch_id' => 'nullable',
            'jobtype_id' => 'nullable|exists:job_types,id,company_id,' . $this->sessionDetails('company_id'),
            'jobrole_id' => 'nullable|exists:job_roles,id,company_id,' . $this->sessionDetails('company_id'),
            'state' => 'nullable|exists:states,id',
            'country' => 'nullable|exists:countries,id',
            'lga' => 'nullable|exists:lgas,id',
            'date_of_employment' => 'nullable',
            'department_id' => 'nullable|exists:departments,id,company_id,' . $this->sessionDetails('company_id'),
            'commission_id' => 'nullable|exists:commissions,id,company_id,' . $this->sessionDetails('company_id'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $data = $request->all();

        $a_details = !empty($staff->additional_details) ? json_decode($staff->additional_details, true) : [];
        $additional_details = [
            'alternate_number' => $request->has('alternate_number') ? $request->alternate_number : $a_details['alternate_number'],
            'alternate_email' => $request->has('alternate_email') ? $request->alternate_email : $a_details['alternate_email'],
            'state' => $request->has('state') ? $request->state : $a_details['state'],
            'residential_address' => $request->has('residential_address') ? $request->residential_address : $a_details['residential_address'],
            'marital_status' => $request->has('marital_status') ? $request->marital_status : $a_details['marital_status'],
            'nationality' => $request->has('nationality') ? $request->nationality : $a_details['nationality'],
            'lga' => $request->has('lga') ? $request->lga : $a_details['lga'],
        ];

        $t_next_of_kin = !empty($staff->next_of_kin) ? json_decode($staff->next_of_kin, true) : [];
        $next_of_kin = [
            'nok_first_name' => $request->has('nok_first_name') ? $request->nok_first_name : $t_next_of_kin['nok_first_name'],
            'nok_last_name' => $request->has('nok_last_name') ? $request->nok_last_name : $t_next_of_kin['nok_last_name'],
            'nok_phone_number' => $request->has('nok_phone_number')  ? $request->nok_phone_number : $t_next_of_kin['nok_phone_number'],
            'nok_house_address' => $request->has('nok_house_address')  ? $request->nok_house_address : $t_next_of_kin['nok_house_address'],
            'nok_email_address' => $request->has('nok_email_address')  ? $request->nok_email_address : $t_next_of_kin['nok_email_address'],
            'nok_relationship' => $request->has('nok_relationship')  ? $request->nok_relationship : $t_next_of_kin['nok_relationship'],
        ];

        $staff->update([
            'jobtype_id' => $request->has('jobtype_id') ? $data['jobtype_id'] :$staff->job_type_id,
            'date_of_employment' => $request->has('date_of_employment') ? $data['date_of_employment'] : $staff->data_of_employment,
            'department_id' => $request->has('department_id') ? $data['department_id'] :$staff->department_id,
            'jobrole_id' => $request->has('jobrole_id') ? $data['jobrole_id'] :$staff->job_role_id,
            'commission_id' => $request->has('commission_id') ? $data['commission_id'] : $staff->commision_percentage,
            'company_id' => $this->sessionDetails('company_id'),
        ]);
       
        // dd(json_encode($next_of_kin));
        $user = $staff->user->update([
            'email' => $request->has('email') ? $data['email'] : $staff->user->email,
            'first_name' => $request->has('first_name') ? $data['first_name'] : $staff->user->first_name,
            'middle_name' => $request->has('middle_name') ? $data['middle_name'] : $staff->user->middle_name,
            'type' => 'staff',
            'last_name' => $request->has('last_name') ? $data['last_name'] : $staff->user->middle_name,
            'username' => $request->has('username') ? $data['username'] : $staff->user->username,
            'phone_number' => $request->has('phone_number') ? $data['phone_number'] : $staff->user->phone,
            'gender' => $request->has('gender')  ? $data['gender'] : $staff->user->gender,
            'additional_details' => json_encode($additional_details),
            'next_of_kin' => json_encode($next_of_kin),
            'branch_id' => $request->has('branch_id')  ? $data['branch_id'] : $staff->user->branch_id,
            'date_of_birth' => $request->has('date_of_birth')  ? $data['date_of_birth'] : $staff->user->date_of_birth,
            'company_id' => $this->sessionDetails('company_id')
        ]);

        // Create staff documents
        // if ($request->staff_documents) {
        //     foreach ($request->staff_documents as $document) {
        //         \DB::table('staff_uploaded_documents')->insert([
        //             'staff_document_id' => $document->id,
        //             'name' =>  $document->name,
        //             'uploaded' =>  $document->uploaded ?? '',
        //             'required' =>  $document->required ?? '',
        //             'company_id' => $this->sessionDetails('company_id'),
        //         ]);
        //     }
        // }

        return response()->json(['message' => 'Successfully updated!', 'data' => ['staff_id' => $request->staff_id]], 200);
    }


    public function getSingleStaffRoles($staff_id)
    {
        $staff = Staff::where(['id' => $staff_id])->first();

        if (!$staff) {
            return response()->json(['error' => 'Staff not found'], 412);
        }

        $roles = $staff->roles->pluck('id')->toArray();

        return response()->json(['message' => 'successfull', 'data' => $roles], 200);
    }

    public function staffIdentificationSetting($id = null)
    {

        $staff_ientification_settings = DB::table('staff_identification_settings')->where(['company_id' => $this->sessionDetails('company_id')])->get();
        return response()->json(['message' => 'successfull', 'data' => $staff_ientification_settings], 200);
    }

    public function updateStaffIdentificationSetting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'use_id_generator' => 'in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        if ($request->use_id_generator == 1) {
            $validator = Validator::make($request->all(), [
                'id_generator_type' => 'nullable|in:employment_year,employment_month,employment_day,auto_increment_number,random_number',
                'next_auto_increment_number' => 'nullable',
                'auto_increment_number_digit' => 'nullable',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $data = $validator->valid();
        $data['company_id'] = $this->sessionDetails('company_id');
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $settings = DB::table('staff_identification_settings')->updateOrInsert($data);

        return response()->json(['message' => 'successfull', 'data' => $settings], 200);
    }

    public function staffDocumentSetting($id = null)
    {
        if (isset($id) && !empty($id)) {
            $settings = DB::table('staff_document_settings')->where(['company_id' => $this->sessionDetails('company_id'), 'id' => $id])->first();
        } else {
            $settings = DB::table('staff_document_settings')->where(['company_id' => $this->sessionDetails('company_id')])->get();
        }
        return response()->json(['message' => 'successfull', 'data' => $settings], 200);
    }

    public function addStaffDocumentSetting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required', Rule::unique('staff_document_settings')->where(function ($query) {
                    $query->where('company_id', $this->sessionDetails('company_id'));
                })
            ],
            'description' => 'nullable',
            'required' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $data = $validator->valid();
        $data['company_id'] = $this->sessionDetails('company_id');
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $settings = DB::table('staff_document_settings')->updateOrInsert($data);

        return response()->json(['message' => 'Operation Successfull', 'data' => $settings], 200);
    }

    public function updateStaffDocumentSetting(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'nullable',
            'required' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $data = $validator->valid();
        $data['updated_at'] = date('Y-m-d H:i:s');

        $settings = DB::table('staff_document_settings')->where(['id' => $id, 'company_id' => $this->sessionDetails('company_id')])->update($data);

        return response()->json(['message' => 'successfull', 'data' => $settings], 200);
    }

    public function deleteStaffDocumentSetting(Request $request, $id)
    {
        $settings = DB::table('staff_document_settings')->where(['id' => $id, 'company_id' => $this->sessionDetails('company_id')])->delete();

        return response()->json(['message' => 'successfull', 'data' => $settings], 200);
    }

    public function getSingleStaffProfile($staff_id, $internal = null)
    {
        $staff = Staff::with('roles')->where(['staff.id' => $staff_id])
            ->leftjoin("users", "staff.user_id", "=", "users.id")->where(['users.status' => 'active', 'type' => 'staff'])
            ->leftjoin("job_types", "staff.jobtype_id", "=", "job_types.id")
            ->leftjoin("job_roles", "staff.jobrole_id", "=", "job_roles.id")
            ->leftjoin("branches", "users.branch_id", "=", "branches.id")
            ->leftjoin("departments", "staff.department_id", "=", "departments.id")
            ->select("staff.id AS staff_id", "users.status", "users.staff_id as employee_id", "staff.created_at", "users.first_name", "users.username", "users.last_name", "users.middle_name", "users.avatar", "users.phone_number", "users.email", "staff.id AS staff_id", "users.type", "users.company_id", "staff.jobtype_id", "users.id AS user_id", "staff.date_of_employment", "branches.id AS branch_id", "branches.name AS branch_name", "departments.id AS department_id", "departments.name AS department_name", "job_roles.id AS job_role_id", "job_roles.name AS job_role_name", "job_types.id AS job_type_id", "job_types.name AS job_type_name", "users.additional_details", "users.next_of_kin", "users.date_of_birth AS date_of_birth", "users.gender")
            ->first();

        $profile_completenes = $this->profileCompletenes($staff);
        $additional_details = !empty($staff->additional_details) ? json_decode($staff->additional_details, true) : [];
        $next_of_kin = !empty($staff->next_of_kin) ? json_decode($staff->next_of_kin, true) : [];

        $time = Carbon::parse($staff->date_of_employment)->diff(Carbon::now());
        $time_in_company = ($time->y > 0 ? $time->y . ($time->y > 1 ? 'years, ' : 'year, ') : '') .  ($time->m > 0 ? ($time->m > 1 ? $time->m . ' months' : $time->m . ' month') : 'Less than a month');
        $my_anniversary = Carbon::parse($staff->date_of_employment)->format('jS\\, F');
        $data['staff_id'] = $staff->staff_id;
        $data['employee_id'] = $staff->employee_id;
        $data['profile_completenes'] = $profile_completenes;
        
        $employment_date = $staff->date_of_employment ? date("jS\\, F Y", strtotime($staff->date_of_employment)) : '';
        
        $data['job_information'] = [
            'job_type' => [
                'id' => $staff->job_type_id ?? '',
                'name' => $staff->job_type_name ?? '',
            ],
            'employment_date' => $employment_date,
            'time_in_company' => $time_in_company,
            'work_anniversary' => $my_anniversary,
            'department' => [
                'id' => $staff->department_id ?? '',
                'name' => $staff->department_name ?? ''
            ],
            'job_role' => [
                'id' => $staff->job_role_id ?? '',
                'name' => $staff->job_role_name ?? '',
            ],
            'role' => [
                'id' => $staff->role->id ?? '',
                'name' => $staff->role->name ?? '',
            ],
            'branch' => [
                'id' => $staff->branch_id ?? '',
                'name' => $staff->branch_name ?? '',
            ]
        ];

        $data['personal_information'] = [
            'first_name' => $staff->first_name ?? '',
            'middle_name' => $staff->middle_name ?? '',
            'last_name' => $staff->last_name ?? '',
            'username' => $staff->user->username ?? '',
            'email' => $staff->user->email ?? '',
            'phone_number' => $staff->user->phone_number ?? '',
            'gender' => $staff->user->gender ?? '',
            'marital_status' => $additional_details['marital_status'] ?? '',
            'dob' => $staff->date_of_birth,
            'avatar' => !empty($staff->avatar) ? url('/') . '/uploads/' . base64_encode($staff->avatar) : '',
        ];

        $data['contact_and_additional_information'] = [
            'residential_address' => $additional_details['residential_address'] ?? '',
            'alternate_email' => $additional_details['alternate_email'] ?? '',
            'alternate_number' => $additional_details['alternate_number'] ?? '',
            'state' => !empty($additional_details['state']) ? $this->getStateDetails($additional_details['state']) : '',
            'lga' => !empty($additional_details['lga']) ? $this->getLgaDetails($additional_details['lga']) : '',
            'nationality' => !empty($additional_details['nationality']) ? $this->getCountryDetails($additional_details['nationality']) : '',
        ];

        $data['next_of_kin_information'] = [
            'first_name' => $next_of_kin['nok_first_name'] ?? '',
            'last_name' => $next_of_kin['nok_last_name'] ?? '',
            'house_address' => $next_of_kin['nok_house_address'] ?? '',
            'email_address' => $next_of_kin['nok_email_address'] ?? '',
            'phone_number' => $next_of_kin['nok_phone_number'] ?? '',
            'relationship' => $next_of_kin['nok_relationship'] ?? '',
        ];

        return response()->json(['message' => 'success', 'data' => $data], 200);
    }

    public function uploadDocuments(Request $request, $self = false)
    {
        $self_service = new SelfServiceController();
        $res = $self_service->uploadDocuments($request, $self);
       
        if(isset($res['error'])){
            return response()->json(['error' => $res['error'], 'data' => $res['data']], $res['code'] ?? 412);
        }else{
            return response()->json(['message' => $res['message'], 'data' => $res['data']], $res['code'] ?? 200);
        }
    }

    public function sampleBulkStaff()
    {
        $realpath = base_path() . '/sampleimports/sample_bulkstaff.xlsx';
        return response()->download($realpath);
    }

    public function importBulkStaff(Request $request)
    {
        $this->validate(request(), [
            'file' => 'required|
				mimetypes:xlsv,xlsx,xls,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,
				application/excel,application/x-excel,application/x-msexcel,text/comma-seperated-values, text/csv'
        ], [
            'file.mimetypes' => 'The file must be a file of type: xlsx',
            'file.required' => 'File is required'
        ]);

        try {
            //code...
            Excel::import(new StaffImport(), request()->file('file'));
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 412);
        }

        return response()->json(['message' => 'Staff Added Succesfully', 'data' => null], 200);
    }
}
