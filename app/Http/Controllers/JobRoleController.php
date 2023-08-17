<?php

namespace App\Http\Controllers;

use App\Models\JobRole;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Imports\JobRoleImport;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class JobRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobroles = JobRole::where('company_id', $this->sessionDetails('company_id'))->get();
        return response()->json(['message' => 'success', 'data' => $jobroles], 200);
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
            'department_id' => 'required|exists:departments,id,company_id,'. $this->sessionDetails('company_id'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $jobrole = JobRole::updateOrcreate([
            'name' => $request->name,
            'department_id' => $request->department_id,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Created successfully', 'data' => $jobrole], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(JobRole $jobRole)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobRole $jobRole)
    {
        return response()->json(['message' => 'success', 'data' => $jobRole], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobRole $jobrole)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'department_id' => 'required|exists:departments,id,company_id,' . $this->sessionDetails('company_id'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 422);
        }

        $jobrole = JobRole::updateOrcreate([
            'name' => $request->name,
            'department_id' => $request->department_id,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Updated successfully', 'data' => $jobrole], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobRole $jobrole)
    {
        $jobrole->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => $jobrole], 200);
    }

    public function importBulkJobRole(Request $request)
    {
        $this->validate(request(), [
            'file' => 'required|
				mimetypes:xlsv,xlsx,xls,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,
				application/excel,application/x-excel,application/x-msexcel,text/comma-seperated-values, text/csv'
        ], [
            'file.mimetypes' => 'The file must be a file of type: xlsx'
        ]);

        Excel::import(new JobRoleImport, request()->file('file'));

        return response()->json(['message' => 'Imported Succesfully', 'data' => null], 200);
    }

    public function sampleBulkJobRole()
    {
        $realpath = base_path() . '/sampleimports/samplejobrole.xlsx';
        return response()->download($realpath);
    }
}
