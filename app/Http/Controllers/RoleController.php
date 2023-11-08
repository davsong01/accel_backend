<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($type=null)
    {
        $type = \Request::get('type');
        
        if (!$type) {
            return response()->json(['error' => 'The role type is required as a query param!'], 412);
        }
        $roles = Role::where('type', $type)
            ->where('company_id', $this->sessionDetails('company_id'))
            ->get();
      
        return response()->json(['message' => 'success', 'data' => $roles], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'access' => 'required',
            'description' => 'nullable',
            'type' => 'required|in:customer,staff',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        if (!empty($request->access)) {
            $allAccess = getPackageAccess();
            $access = explode(',', $request->access);

            $allowed = [];
            // check if the request access exists in all access, if no remove it
            foreach ($access as $key => $value) {
                if (!in_array($value, $allAccess)) {
                    unset($access[$key]);
                }
                $allowed = $access;
            }

            $access_control = implode(',', $allowed);
        }

        Role::updateOrcreate(['name' => $request->name, 'company_id' => $this->sessionDetails('company_id'), 'type' => $request->type, 'department_id' => $request->department_id],
            [
                'access' => $access_control,
                'description' => $request->description
            ]
        );

        return response()->json(['message' => 'Created successfully', 'data' => []], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return response()->json(['message' => 'success', 'data' => $role], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        // dd($role);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'access' => 'required',
            'description' => 'nullable',
            'type' => 'required|in:customer,staff',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        if (!empty($request->access)) {
            $allAccess = getPackageAccess();
            $access = explode(',', $request->access);

            $allowed = [];
            // check if the request access exists in all access, if no remove it
            foreach ($access as $key => $value) {
                if (!in_array($value, $allAccess)) {
                    unset($access[$key]);
                }
                $allowed = $access;
            }

            $access_control = implode(',', $allowed);
        }

        $role->update([
            'name' => $request->name,
            'access' => $access_control,
            'description' => $request->description,
            'type' => $request->type,
        ]);

        return response()->json(['message' => 'Role Updated Succesfully', 'data' => $role], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }
}