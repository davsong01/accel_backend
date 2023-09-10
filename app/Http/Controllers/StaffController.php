<?php

namespace App\Http\Controllers;

use App\Models\Role;
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
            ->join("jobroles", "staff.jobrole_id", "=", "jobroles.id")
            ->join("jobtypes", "staff.jobtype_id", "=", "jobtypes.id")
            ->select("staff.id AS staff_id", "users.id As user_id", "users.status", "staff.created_at", "users.first_name", "users.last_name", "users.email", "users.staff_id", "users.type", "users.company_id");


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
            ->select("staff.id AS staff_id", "users.id As user_id", "users.status", "staff.created_at", "users.first_name", "users.last_name", "users.email", "users.staff_id", "users.type", "users.company_id")
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
        dd($request->all());
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
