<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Admin;
use App\Models\AdminRole;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function getActiveAdminsInRole($id)
    {
        $admins = AdminRole::where("role_id", $id)
            ->join("admins", "admin_roles.admin_id", "=", "admins.id")
            ->where('admins.company_id', $this->sessionDetails('company_id'))
            ->join("users", "admins.user_id", "=", "users.id")->where(['users.status'=>'active', 'type'=>'admin'])
            ->select("admins.id AS admin_id", "users.id As user_id","users.status", "admins.created_at", "users.first_name", "users.last_name", "users.email", "users.staff_id", "users.type", "users.company_id")
            ->orderBy("admin_id", "desc")
            ->groupBy("admins.id")
            ->get();
       
        return response()->json(['message' => 'success', 'data' => $admins], 200);
        
    }

    public function getAllAdmins(Request $request){

        $admins = Admin::where(['admins.company_id' => $this->sessionDetails('company_id')])
            ->join("users", "admins.user_id", "=", "users.id")
            ->where(['type' => 'admin'])
            ->select("admins.id AS admin_id", "users.id As user_id", "users.status", "admins.created_at", "users.first_name", "users.last_name", "users.email", "users.staff_id", "users.type", "users.company_id");


        if ($request->has('name') && !is_null($request->status)) {
            $admins = $admins->where('users.first_name', 'LIKE', "%{$request->name}%")
            ->orWhere('users.last_name', 'LIKE', "%{$request->name}%");
        }

        if ($request->has('status') && !is_null($request->status)) {
            $admins = $admins->where('users.status', $request->status);
        }

        $admins = $admins->orderBy("admins.created_at", "desc")->get();

        return response()->json(['message' => 'success', 'data' => $admins], 200);
    }

    public function getActiveAdmins()
    {
        $admins = Admin::where(['admins.company_id'=>$this->sessionDetails('company_id')])
        ->join("users", "admins.user_id", "=", "users.id")->where(['users.status' => 'active', 'type' => 'admin'])
        ->select("admins.id AS admin_id", "users.id As user_id", "users.status", "admins.created_at", "users.first_name", "users.last_name", "users.email", "users.staff_id", "users.type", "users.company_id")
        ->orderBy("admins.created_at", "desc")
        ->get();
        return response()->json(['message' => 'success', 'data' => $admins], 200);
    }

    public function getMultipleRoleAdmins(Request $request)
    {
        // $departments = AdminDepartment::where(['status' => 'active', 'domain_id' => \Request::get('domain_id')])->orderBy('id', 'DESC')->get();

        // foreach ($departments as $department) {
        //     $departmentRoles = Role::where(['type' => 'admin', 'admin_department_id' => $department->id, 'status' => 'active',])->get()->pluck('id');

        //     $department['admins'] = AdminRole::whereIn("role_id", $departmentRoles)
        //         ->join("admins", "admin_roles.admin_id", "=", "admins.id")->where(['admins.status' => 'active'])
        //         ->join("users", "admins.user_id", "=", "users.id")
        //         ->select("admins.id", "admins.status", "admins.type", "admins.created_at", "users.name")
        //         ->orderBy("admins.created_at", "desc")
        //         ->groupBy("admins.id")
        //         ->get();
        // }

        // return response()->json(['message' => 'successfull', 'data' => $departments], 200);
    }

    public function assignRoleToAdmin(Request $request){
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'admin_id' => 'required|exists:admins,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 422);
        }
        
        $assign = AdminRole::updateOrCreate([
            'role_id' => $request->role_id,
            'admin_id' => $request->admin_id,
            'company_id' => $this->sessionDetails('company_id'),
            'branch_id' => $this->sessionDetails('branch_id'),
        ]);

        return response()->json(['message' => 'Created successfully', 'data' => $assign], 200);

    }

    public function storeAdmin(Request $request){
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'admin_id' => 'required|exists:admins,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 422);
        }
    }

    public function getSingleAdminRoles($admin_id){
        $admin = Admin::where(['id' => $admin_id])->first();

        if(!$admin){
            return response()->json(['error'=>'Admin not found'], 422);
        }

        $roles = $admin->roles->pluck('id')->toArray();

        return response()->json(['message' => 'successfull', 'data' => $roles], 200);
        
    }
}
