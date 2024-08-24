<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches = Branch::where('company_id', sessionDetails('company_id'))->get();
        if ($branches->count() > 0) {
            return response()->json(['message' => 'Retrieved Successfully', 'data' => $branches], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => $branches], 200);
        }
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
            'location' => 'sometimes',
            'name' => [
                'required', Rule::unique('branches')->where(function ($query) {
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

        $branch = Branch::updateOrcreate([
            'name' => $request->name,
            'location' => $request->location,
            'company_id' => sessionDetails('company_id'),
        ]);

        LogService::admin(auth()->user()->id, NULL, 'Create or update a branch ('. $branch->id . ')', 'CREATE');
        return response()->json(['message' => 'Created successfully', 'data' => $branch], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'location' => 'sometimes',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $branch->update([
            'name' => $request->name,
            'location' => $request->location,
            'status' => $request->status,
            'company_id' => sessionDetails('company_id'),
        ]);

        LogService::admin(auth()->user()->id, NULL, 'Update a branch ('. $branch->id . ')', 'UPDATE');
        return response()->json(['message' => 'Updated Successfully.', 'data' => $branch], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        LogService::admin(auth()->user()->id, NULL, 'Delete a branch ('. $branch->id . ')', 'DELETE');

        $branch->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }
}
