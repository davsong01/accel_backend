<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::where('company_id', $this->sessionDetails('company_id'))->get();

        return response()->json(['message' => 'success', 'data' => $departments], 200);
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
            'description' => 'sometimes',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $department = Department::updateOrcreate([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Department Added Succesfully', 'data' => $department], 200);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        return response()->json(['message' => 'success', 'data' => $department], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'sometimes',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $department->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Department Updated Succesfully', 'data' => $department], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return response()->json(['message' => 'Delete Successful', 'data' => null], 200);
    }
}
