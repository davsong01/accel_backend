<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\EmployeeExit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class EmployeeExitController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:Voluntary Resignation,Requested Resignation,Termination,Demise,Retirement,Dismissal',
            'last_day_work' => 'required',
            'reason' => 'required|in:Better Renumeration,Career Advancement,Relocation,Performance', 
            'comment' => 'nullable'
        ]);

        if (\Request::route()->getPrefix() != 'api/admin/self-service') {
            $validator = Validator::make($request->all(), [
                'staff_id' => 'required|exists:staff,id,company_id,' . $this->sessionDetails('company_id'),
            ]);
        }
       
        if ($request->type == 'advance') {
            $validator = Validator::make($request->all(), [
                'amount' => 'required',
                'time_sensitivity' => 'required',
            ]);
        }
        
        if (\Request::route()->getPrefix() == 'api/admin/self-service') {
            $staff_id = Auth::user()->staff->id;
        }

        $last_day = Carbon::parse($request->last_day_of_work);
        
        EmployeeExit::updateOrCreate(['staff_id' => $request->staff_id ?? $staff_id, 'type' => $request->type],[
            'last_day_work' => $last_day,
            'reason' => $request->reason,
            'comment' => $request->comment,
            'company_id' => $this->sessionDetails('company_id')
        ]);

        return response()->json(['message' => 'success', 'data' => []], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(EmployeeExit $employeeExit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmployeeExit $employeeExit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmployeeExit $employeeExit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeExit $employeeExit)
    {
        //
    }
}
