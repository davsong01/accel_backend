<?php

namespace App\Http\Controllers;

use App\Imports\JobTitleImport;
use App\Models\JobTitle;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class JobTitleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = $this->sessionDetails('company_id');

        $jobs = JobTitle::with(['department', 'company'])->where('company_id', $company_id)->latest()->paginate(20);
        return response()->json(['message' => 'Success', 'data' => $jobs], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $company_id = $this->sessionDetails('company_id');

        $validator = Validator::make($request->all(), [
            'title' => ['required', Rule::unique('job_titles')->where(function ($query) use ($request) {
                $query->where('title', $request->title);
                $query->where('department_id', $request->department_id);
            })],
            'department_id' => 'required|exists:departments,id,company_id,' . $company_id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $attributes = [...$validator->validated(), 'company_id' => $company_id];
        $created = JobTitle::updateOrCreate($attributes);

        LogService::admin(auth()->user()->id, NULL, 'New job title added (' . $created->id . ')', 'CREATE');
        return response()->json(['message' => 'Success', 'data' => $created], 200);
    }

    public function getJobRoleByDepartmentId($id)
    {
        $jobtitles = JobTitle::where('department_id', $id)
            ->where('company_id', $this->sessionDetails('company_id'))
            ->select('id', 'department_id', 'title')
            ->get();

        return response()->json(['message' => 'Success', 'data' => $jobtitles], 200);
    }

    /**
     * Store a bulk of newly created resources in storage.
     */
    public function bulk(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|
                    mimetypes:xlsv,xlsx,xls,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,
                    application/excel,application/x-excel,application/x-msexcel,text/comma-seperated-values, text/csv'
            ], [
                'file.mimetypes' => 'The file must be a file of type: xlsx'
            ]);
    
    
            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->messages()->first(),
                    'data' => null,
                ], 412);
            }
    
            Excel::import(new JobTitleImport, $request->file('file'));
    
            LogService::admin(auth()->user()->id, NULL, 'Import a bulking job titles', 'CREATE');
            return response()->json(['message' => 'Job titles successfully', 'data' => null], 200);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            foreach ($failures as $failure) {
                $errors = $failure->errors();
                return response()->json([
                    'message' => reset($errors),
                    'error' =>  [
                        'row' => $failure->row(),
                        'attribute' => $failure->attribute(),
                        'errors' => $errors,
                        'values' => array_filter($failure->values()),
                    ],
                    'data' => null,
                ], 412);
        }
    }
    }
    /**
     * Display the specified resource.
     */
    public function show(JobTitle $title)
    {
        $title->load(['department', 'company']);
        return response()->json(['message' => 'Success', 'data' => $title], 200);
    }

    public function edit(JobTitle $jobtitle)
    {
        return response()->json(['message' => 'Success', 'data' => $jobtitle->load('department:id,name')], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobTitle $title)
    {
        $company_id = $this->sessionDetails('company_id');

        $validator = Validator::make($request->all(), [
            'department_id' => 'exists:departments,id,company_id,' . $company_id,
            'title' => 'unique:job_titles,title,' . $title->id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $attributes = $validator->validated();
        $title->update($attributes);

        LogService::admin(auth()->user()->id, NULL, 'Update a job title (' . $title->id . ')', 'UPDATE');
        return response()->json(['message' => 'Success', 'data' => $title->load(['department', 'company'])], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobTitle $title)
    {
        LogService::admin(auth()->user()->id, NULL, 'Delete a job title (' . $title->id . ')', 'DELETE');

        $title->delete();
        return response()->json(['message' => 'Success', 'data' => null], 200);
    }

    /**
     * Bulk resource upload sample file
     */
    public function sample()
    {
        $realpath = base_path() . '/sampleimports/sample_jobtitle.xlsx';
        return response()->download($realpath);
    }
}
