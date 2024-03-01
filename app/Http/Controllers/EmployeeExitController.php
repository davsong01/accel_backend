<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\EmployeeExit;
use App\Models\Staff;
use App\Models\User;
use App\Services\LogService;
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
    public function index(Request $request, $staff_id = null)
    {

        $exit = EmployeeExit::with('staff.user')->where('company_id', $this->sessionDetails('company_id'))->orderBy('created_at', 'DESC');

        if (!empty($staff_id)) {
            $exit = $exit->where('staff_id', $staff_id);
        }

        if (!empty($request->paginate) && $request->paginate == 'false') {
            $exit = $exit->get();
        } else {
            $exit = $exit->paginate(20)->withQueryString();
        }

        return response()->json(['message' => 'Success', 'data' => $exit], 200);
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
            'reason' => 'required|in:Better Remuneration,Career Advancement,Relocation,Performance',
            'comment' => 'nullable'
        ]);

        if (\Request::route()->getPrefix() != 'api/admin/self-service') {
            $validator = Validator::make($request->all(), [
                'staff_id' => 'required|exists:staff,id,company_id,' . $this->sessionDetails('company_id'),
            ]);
        }

        if ($request->type == 'Voluntary Resignation') {
            $validator = Validator::make($request->all(), [
                'resignation_letter' => 'required',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        if (\Request::route()->getPrefix() == 'api/admin/self-service') {
            $staff_id = Auth::user()->staff->id;
        }

        $last_day = Carbon::parse($request->last_day_of_work);
        $resignation_letter = !empty($request->resignation_letter) ? $this->uploadFile($request->resignation_letter, 'exits') : null;

        $ee = EmployeeExit::updateOrCreate(['staff_id' => $request->staff_id ?? $staff_id, 'type' => $request->type], [
            'last_day_work' => $last_day,
            'reason' => $request->reason,
            'file' => $resignation_letter,
            'comment' => $request->comment,
            'company_id' => $this->sessionDetails('company_id')
        ]);

        $staff = Staff::find($request->staff_id ?? $staff_id);
        LogService::admin(auth()->user()->id, $staff->user->id, 'Create or update an employee exit, ' .  $request->reason . ' (' . $ee->id . ')', 'CREATE');
        return response()->json(['message' => 'Success', 'data' => []], 200);
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
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:PENDING,APPROVED,DECLINED',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $employeeExit->update($validator->validated());

        LogService::admin(auth()->user()->id, $employeeExit->staff->user->id, 'Update an employee exit to ' . $request->status . '(' . $employeeExit->id . ')', 'UPDATE');
        return response()->json(['message' => 'Successfully updated', 'data' => $employeeExit], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmployeeExit $employeeExit)
    {
        //
    }

    public function search(Request $request)
    {
        $params = $request->only(['status']);
        $company_id = $this->sessionDetails('company_id');

        $query = EmployeeExit::with([
            'staff:id,user_id', 'staff.user:id,first_name,middle_name,last_name',
        ])->where('company_id', $this->sessionDetails('company_id'))
            ->where($params)->orderBy('created_at', 'DESC');

        $from = $request->input('from');
        $to = $request->input('to');
        if ($from && $to){
            $from = Carbon::parse($request->input('from'));

            $to = Carbon::parse($request->input('to'));
            if ($to->format('H:i:s') == '00:00:00')
                $to->endOfDay();

            $query->whereBetween('created_at', [$from, $to]);
        } 

        if ($request->input('staff_name') && $name = $request->input('staff_name')) {
            $users = User::with(['staff'])->where('company_id', $company_id)->whereNotNull('company_id')
                ->where('first_name', 'LIKE', '%' . $name . '%')
                ->orWhere('last_name', 'LIKE', '%' . $name . '%')
                ->orWhere('middle_name', 'LIKE', '%' . $name . '%')->get();
            $staff_ids = $users->whereNotNull('staff.id')->pluck('staff.id')->toArray();

            $query->whereIn('staff_id', $staff_ids);
        }

        $employeeExits = $query->latest()->paginate(20);

        return response()->json(['message' => 'Success', 'data' => $employeeExits], 200);
    }
}
