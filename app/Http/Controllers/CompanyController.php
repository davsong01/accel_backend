<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Company;
use App\Models\StaffRole;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_id' => 'required|exists:packages',
            'name' => 'required',
            'location' => 'nullable',
            'status' => 'nullable',
            'url' => 'nullable',
            'subscription_start' => 'required',
            'subscription_end' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        // Create company
        $company = Company::create([
            'package_id' => $request->package_id,
            'name' => $request->name,
            'location' => $request->location,
            'url' => $request->url,
            'subscription_start' => Carbon::parse($request->subscription_start),
            'subscription_end' => Carbon::parse($request->subscription_end),
            'status' => $request->status,
        ]);

        $default1 = User::create([
            'email' => 'accel001@accel234.com',
            'first_name' => 'System',
            'type' => 'staff',
            'middle_name' => '',
            'last_name' => 'Administrator',
            'username' => 'sys-administrator',
            'phone_number' => '',
            'gender' => 'male',
            'company_id' => $company->id,
            'staff_id' => env('DEFAULT_STAFF_ID_1'),
        ]);

        // Create staff
        $staff = Staff::create([
            'jobtype_id' => $data['jobtype_id'] ?? '',
            'user_id' => $default1->id,
            'company_id' => $company->id,
        ]);

        // Assign roles
        StaffRole::updateOrCreate([
            'role_id' => env('DEFAULT_ACC_ROLE_ID'),
            'staff_id' => $staff->id,
            'company_id' => $company->id,
        ]);

        ///////////
        $default2 = User::create([
            'email' => 'accel002@accel234.com',
            'first_name' => 'System',
            'type' => 'staff',
            'middle_name' => '',
            'last_name' => 'Administrator',
            'username' => 'sys-administrator',
            'phone_number' => '',
            'gender' => 'female',
            'company_id' => $company->id,
            'staff_id' => env('DEFAULT_STAFF_ID_2'),
        ]);

        // Create staff
        $staff2 = Staff::create([
            'jobtype_id' => $data['jobtype_id'] ?? '',
            'user_id' => $default2->id,
            'company_id' => $company->id,
        ]);

        // Assign roles
        $assign = StaffRole::updateOrCreate([
            'role_id' => env('DEFAULT_ACC_ROLE_ID'),
            'staff_id' => $staff2->id,
            'company_id' => $company->id,
        ]);

        return response()->json(['message' => 'Created Successfully.', 'data' => []], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $validator = Validator::make($request->all(), [
            'package_id' => 'required|exists:packages',
            'name' => 'required',
            'location' => 'nullable',
            'status' => 'nullable',
            'url' => 'nullable',
            'subscription_start' => 'required',
            'subscription_end' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        // Create company
        $company->update([
            'package_id' => $request->package_id,
            'name' => $request->name,
            'location' => $request->location,
            'url' => $request->url,
            'subscription_start' => Carbon::parse($request->subscription_start),
            'subscription_end' => Carbon::parse($request->subscription_end),
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Updated Successfully.', 'data' => []], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
    }
}
