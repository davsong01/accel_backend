<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Staff;
use App\Models\StaffRole;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    public function getActiveStaffInRole($id)
    {
        $staff = StaffRole::where("role_id", $id)
            ->join("staff", "staff_roles.staff_id", "=", "staff.id")
            ->where('staff.company_id', $this->sessionDetails('company_id'))
            ->join("users", "staff.user_id", "=", "users.id")->where(['users.status' => 'active', 'type' => 'staff'])
            ->select("staff.id AS staff_id", "users.id As user_id", "users.status", "staff.created_at", "users.first_name", "users.last_name", "users.email", "users.staff_id", "users.type", "users.company_id")
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
            ->select("staff.id AS staff_id", "users.id As user_id", "users.status", "staff.created_at", "users.first_name", "users.last_name", "users.email", "users.staff_id", "users.type", "users.company_id","job_roles.name AS job_role","job_types.name AS job_type", "departments.name AS department", "users.middle_name", "users.email", "users.staff_id", "users.type", "users.phone_number", "users.gender");
       
        if ($request->has('name') && !is_null($request->status)) {
            $staff = $staff->where('users.first_name', 'LIKE', "%{$request->name}%")
                ->orWhere('users.last_name', 'LIKE', "%{$request->name}%");
        }

        if ($request->has('status') && !is_null($request->status)) {
            $staff = $staff->where('users.status', $request->status);
        }

        $staff = $staff->orderBy("staff.created_at", "desc")->get();

        return response()->json(['message' => 'success', 'data' => $staff], 200);
    }

    public function getActiveStaff()
    {
        $staff = Staff::where(['staff.company_id' => $this->sessionDetails('company_id')])
            ->join("users", "staff.user_id", "=", "users.id")->where(['users.status' => 'active', 'type' => 'staff'])
            ->select("staff.id AS staff_id", "users.id As user_id", "users.status", "staff.created_at", "users.first_name", "users.last_name", "users.middle_name", "users.email", "users.staff_id", "users.type", "users.phone_number", "users.gender", "users.company_id")
            ->orderBy("staff.created_at", "desc")
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
        if(!isset($request->staff_id) && empty($request->staff_id)){
            $validator = Validator::make($request->all(), [
                'email' => [
                    'required', 'email', Rule::unique('users')->where(function ($query) {
                        $query->where('company_id', $this->sessionDetails('company_id'));
                    })
                ],
                'first_name' => 'required',
                'middle_name' => 'nullable',
                'last_name' => 'required',
                'username' => 'required',
                'phone' => 'required',
                'gender' => 'required',
                'branch_id' => 'required',
                'jobtype_id' => 'required',
                'date_of_employment' => 'required',
                'department_id' => 'required',
                'jobrole_id' => 'required',
                'commision_percentage' => 'required'
            ]);
        }
        $validator = Validator::make($request->all(), [
            'date_of_birth' => 'required',
            'alternate_number' => 'nullable',
            'state' => 'required',
            'residential_address' => 'required',
            'marital_status' => 'required',
            'nationality' => 'required',
            'lga' => 'required',
            
            'nok_first_name' => 'required',
            'nok_last_name' => 'required',
            'nok_phone_number' => 'required',
            'nok_house_address' => 'required',
            'nok_email_address' => 'required',
            'nok_relationship' => 'required',

            'staff_documents' => 'nullable',
        ]);
        

        // Additional_information'
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $data = $validator->valid();
        $data['addition_details'] = json_encode([
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'alternate_number' => $data['alternate_number'] ?? null,
            'state' => $data['state'] ?? null,
            'residential_address' => $data['residential_address'] ?? null,
            'marital_status' => $data['marital_status'] ?? null,
            'nationality' => $data['nationality'] ?? null,
            'lga' => $data['lga'] ?? null,
        ]);
        
        $data['next_of_kin'] = json_encode([
            'nok_first_name' => $data['nok_first_name'],
            'nok_last_name' => $data['nok_last_name'],
            'nok_phone_number' => $data['nok_phone_number'],
            'nok_house_address' => $data['nok_house_address'],
            'nok_email_address' => $data['nok_email_address'],
            'nok_relationship' => $data['nok_relationship'],
        ]);

        dd($data);
        // Create user
        if (!isset($request->staff_id) && empty($request->staff_id)) {
            $user = User::create([
                'email' => $data['email'],
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'last_name' => $data['last_name'] ?? null,
                'username' => $data['username'] ?? null,
                'phone' => $data['phone'] ?? null,
                'gender' => $data['gender'] ?? null,
                'addition_details' => $data['addition_details'],
                'next_of_kin' => $data['next_of_kin'],
                'branch_id' => $data['branch_id'] ?? null,
                'company_id' => $this->sessionDetails('company_id'),
            ]);
            
            
            // Create staff
            $staff = Staff::create([
                'jobtype_id' => $data['jobtype_id'] ?? null,
                'date_of_employment' => $data['date_of_employment'] ?? null,
                'department_id' => $data['department_id'] ?? null,
                'jobrole_id' => $data['jobrole_id'] ?? null,
                'commision_percentage' => $data['commision_percentage'] ?? null,
                'company_id' => $this->sessionDetails('company_id'), 
            ]);
                
        }else{
            $staff = Staff::where('id', $request->staff_id)->where('company_id', $this->sessionDetails('company_id'));
            $user = $staff->user();
        }

        // Create staff documents
        if($request->staff_documents){
            foreach($request->staff_documents as $document){
                \DB::table('staff_uploaded_documents')->insert([
                    'staff_document_id' => $document->id,
                    'name' =>  $document->name,
                    'uploaded' =>  $document->uploaded ?? null,
                    'required' =>  $document->required ?? null,
                    'company_id' => $this->sessionDetails('company_id'),
                ]);
            }
           
        }

        return response()->json(['message' => 'Successfully created!', 'data' => $user], 200);

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

    public function staffIdentificationSetting($id=null){
        
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

    public function staffDocumentSetting($id=null){
        if (isset($id) && !empty($id)) {
            $settings = DB::table('staff_document_settings')->where(['company_id' => $this->sessionDetails('company_id'), 'id' => $id])->first();
           
        } else {
            $settings = DB::table('staff_document_settings')->where(['company_id' => $this->sessionDetails('company_id')])->get();
        }
        return response()->json(['message' => 'successfull', 'data' => $settings], 200);
    }

    public function addStaffDocumentSetting(Request $request){
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
        $data['company_id'] = $this->sessionDetails('company_id');
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $settings = DB::table('staff_document_settings')->updateOrInsert($data);

        return response()->json(['message' => 'successfull', 'data' => $settings], 200);
    }

    public function updateStaffDocumentSetting(Request $request, $id){
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

        $settings = DB::table('staff_document_settings')->where(['id'=>$id, 'company_id'=> $this->sessionDetails('company_id')])->update($data);

        return response()->json(['message' => 'successfull', 'data' => $settings], 200);

    }

    public function deleteStaffDocumentSetting(Request $request, $id)
    {
        $settings = DB::table('staff_document_settings')->where(['id' => $id, 'company_id' => $this->sessionDetails('company_id')])->delete();

        return response()->json(['message' => 'successfull', 'data' => $settings], 200);
    }
}
