<?php

namespace App\Http\Controllers;

use App\Models\JobType;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class JobTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobtype = JobType::where('company_id', sessionDetails('company_id'))->get();
        return response()->json(['message' => 'Success', 'data' => $jobtype], 200);
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
                'required', Rule::unique('job_roles')->where(function ($query) {
                    $query->where('company_id', sessionDetails('company_id'));
                })
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $jobtype = Jobtype::updateOrcreate([
            'name' => $request->name,
            'company_id' => sessionDetails('company_id'),
        ]);

        LogService::admin(auth()->user()->id, null, 'Create or update a job type (' . $jobtype->id  . ')' , 'CREATE');
        return response()->json(['message' => 'Created successfully', 'data' => $jobtype], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(JobType $jobtype)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobType $jobtype)
    {
        return response()->json(['message' => 'Success', 'data' => $jobtype], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobType $jobtype)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $jobtype->update([
            'name' => $request->name,
            'company_id' => sessionDetails('company_id'),
        ]);

        LogService::admin(auth()->user()->id, null, 'Update a job role (' . $jobtype->id  . ')' , 'UPDATE');
        return response()->json(['message' => 'Updated successfully', 'data' => $jobtype], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobType $jobtype)
    {
        LogService::admin(auth()->user()->id, null, 'Update a job type (' . $jobtype->id  . ')' , 'DELETE');

        $jobtype->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => $jobtype], 200);
    }
}
