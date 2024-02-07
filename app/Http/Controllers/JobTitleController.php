<?php

namespace App\Http\Controllers;

use App\Imports\JobTitlesImport;
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
        return response()->json(['message' => 'success', 'data' => $created], 200);
    }

    /**
     * Store a bulk of newly created resources in storage.
     */
    public function bulk(Request $request)
    {
        Validator::make($request->all(), [
            'job_titles' => 'required|
				mimetypes:xlsv,xlsx,xls,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,
				application/excel,application/x-excel,application/x-msexcel,text/comma-seperated-values, text/csv'
        ], [
            'file.mimetypes' => 'The file must be a file of type: xlsx'
        ]);

        Excel::import(new JobTitlesImport, request()->file('job_titles'));

        LogService::admin(auth()->user()->id, NULL, 'Import a bulking job titles', 'CREATE');
        return response()->json(['message' => 'Building Type Added Successfully', 'data' => null], 200);
        Excel::import(new JobTitlesImport, request()->file('your_file'));
    }
    /**
     * Display the specified resource.
     */
    public function show(JobTitle $title)
    {
      $title->load(['department', 'company']);
        return response()->json(['message' => 'success', 'data' => $title], 200);
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
        return response()->json(['message' => 'success', 'data' => $title->load(['department', 'company'])], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobTitle $title)
    {
        LogService::admin(auth()->user()->id, NULL, 'Delete a job title (' . $title->id . ')', 'DELETE');

        $title->delete();
        return response()->json(['message' => 'success', 'data' => null], 200);
    }
}
