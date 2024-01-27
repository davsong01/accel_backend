<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Imports\DepartmentImport;
use App\Services\LogService;
use Maatwebsite\Excel\Facades\Excel;
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
        return response()->json(['message' => 'Success', 'data' => $departments], 200);
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
            'name' => [
                'required', Rule::unique('departments')->where(function ($query) {
                    $query->where('company_id', $this->sessionDetails('company_id'));
                })
            ],
            'description' => 'sometimes',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $department = Department::updateOrcreate(['name' => $request->name, 'company_id' => $this->sessionDetails('company_id')], [
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        LogService::admin(auth()->user()->id, NULL, 'Create or update a company department (' . $department->id .')', 'CREATE');
        return response()->json(['message' => 'Created successfully', 'data' => $department], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        return response()->json(['message' => 'Success', 'data' => $department], 200);
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

        LogService::admin(auth()->user()->id, NULL, 'Update a company department (' . $department->id .')', 'UPDATE');
        return response()->json(['message' => 'Department Updated Successfully', 'data' => $department], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        if ($this->definedRelations('Department', $department) > 0) {
            return response()->json(['message' => 'Unable to delete, resource in use', 'data' => []], 412);
        }

        LogService::admin(auth()->user()->id, NULL, 'Delete a company department (' . $department->id .')', 'DELETE');
        $department->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }

    public function importBulkDepartment(Request $request)
    {
        $this->validate(request(), [
            'file' => 'required|
				mimetypes:xlsv,xlsx,xls,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,
				application/excel,application/x-excel,application/x-msexcel,text/comma-seperated-values, text/csv'
        ], [
            'file.mimetypes' => 'The file must be a file of type: xlsx'
        ]);

        Excel::import(new DepartmentImport, request()->file('file'));

        return response()->json(['message' => 'Department Imported Succesfully', 'data' => null], 200);
    }

    public function sampleBulkDepartment()
    {
        $realpath = base_path() . '/sampleimports/sampledepartment.xlsx';
        return response()->download($realpath);
    }
}
