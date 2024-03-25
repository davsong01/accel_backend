<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Models\AdvanceAndClaims;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\AdvanceAndClaimsSetting;
use App\Models\Staff;
use App\Services\LogService;
use Illuminate\Support\Facades\Validator;

class AdvanceAndClaimsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id = null)
    {
        $aac = AdvanceAndClaims::where('company_id', $this->sessionDetails('company_id'));
        
        if (\Request::route()->getPrefix() == 'api/admin/self-service') {
            $staff_id = Auth::user()->staff->id;
            $aac = $aac->where('staff_id',$staff_id);
        }

        if (isset($request->type) && !empty($request->type)) {
            $aac = $aac->where('type', $request->type);
        }

        if (isset($request->status) && !empty($request->status)) {
            $aac = $aac->where('status', $request->status);
        }

        if (isset($id) && !empty($id)) {
            $aac = $aac->where('id', $id)->first();
            if ($aac) {
                if (!empty($aac->staff_id)) {
                    $aac->request_by = [
                        'first_name' => $aac->staff->user->first_name,
                        'middle_name' => $aac->staff->user->middle_name,
                        'last_name' => $aac->staff->user->last_name,
                        'id' => $aac->staff->id,
                    ];
                }
                return response()->json(['message' => 'Retrieved Successfully', 'data' => $aac], 200);
            } else {
                return response()->json(['message' => 'No records found', 'data' => $aac], 200);
            }
        }

        $aac = $aac->orderBy('created_at', 'DESC')->paginate(20)->withQueryString();

        foreach($aac as $l){
           
            if (!empty($l->staff_id)) {
                $l->request_by = [
                    'first_name' => $l->staff->user->first_name ?? '',
                    'middle_name' => $l->staff->user-> middle_name ?? '',
                    'last_name' => $l->staff->user-> last_name ?? '',
                    'id' => $l->staff->id ?? '',
                ];
            }
        }
        if ($aac->count() > 0) {
            return response()->json(['message' => 'Retrieved Successfully', 'data' => $aac], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => $aac], 200);
        }
    }

    public function staffAdvanceAndClaims(Request $request, $staff_id = null){
        if (\Request::route()->getPrefix() == 'api/admin/self-service') {
            $staff_id = Auth::user()->staff->id;
        } 
        $lam = AdvanceAndClaims::where('company_id', $this->sessionDetails('company_id'))->where('staff_id',$staff_id);
       
        if (isset($request->type) && !empty($request->type)) {
            $lam = $lam->where('type', $request->type);
        }

        if (isset($request->status) && !empty($request->status)) {
            $lam = $lam->where('status', $request->status);
        }

        if (isset($id) && !empty($id)) {
            $lam = $lam->where('id', $id)->first();
            if ($lam) {
                return response()->json(['message' => 'Retrieved Successfully', 'data' => $lam], 200);
            } else {
                return response()->json(['message' => 'No records found', 'data' => $lam], 200);
            }
        }

        $lam = $lam->orderBy('created_at', 'DESC')->paginate(20)->withQueryString();

        if ($lam->count() > 0) {
            return response()->json(['message' => 'Retrieved Successfully', 'data' => $lam], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => $lam], 200);
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
            'type' => 'required|in:advance,claims',
            'bypass_approval' => 'sometimes',
            'purpose' => 'required'
        ]);

        if($request->type == 'advance'){
            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric',
                'time_sensitivity' => 'required',
            ]);
        }

        if ($request->type == 'claims') {
            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric',
                'date' => [
                    'required',Rule::unique('advance_and_claims')->where(function ($query) {
                        $query->where('company_id', $this->sessionDetails('company_id'));
                    })
                ],
                'proof' => 'nullable',
            ]);
        }

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $request['date'] = !empty($request->date) ? Carbon::parse($request->start_date . ' 00:00:00') : null;
        $proof = [];
        if(!empty($request->proof)){
            foreach($request->proof as $p){
                $proof[] = $this->uploadFile($p, 'advance_claims');
            }
        }

        if (!empty($request->bypass_approval) && $request->bypass_approval == 'Yes') {
            $status = 'approved';
        }

        $staff = Staff::find($request->input('staff_id', Auth::user()->staff->id));

        $advance_and_claims = AdvanceAndClaims::create([
            'type' => $request->type,
            'purpose' => $request->purpose,
            'staff_id' => $staff->id, 
            'period' => $request->period ?? null,
            'date' => $request->date ?? null,
            'amount' => $request->amount,
            'purpose' => $request->purpose,
            'time_sensitivity' => $request->amount,
            'proof' => json_encode($proof),
            'status' => $status ?? 'pending',
            'company_id' => $this->sessionDetails('company_id')
        ]);

        $staff = Staff::find($request->input('staff_id', Auth::user()->staff->id));

        LogService::admin(auth()->user()->id, $staff->user->id, 'Create an advance and claims ('. $advance_and_claims->id . ')', 'CREATE');
        return response()->json(['message' => 'Created successfully', 'data' => null], 200);

    }

    public function approveAdvanceAndClaims($id)
    {
        $lam = AdvanceAndClaims::find($id);
        
        if ($lam->status == 'approved') {
            return response()->json(['message' => 'Already Approved', 'data' => null], 200);
        }

        $lam->update(['status' => 'approved']);

        $data = [
            'title' => ucfirst($lam->type).' Request Approved',
            'content' => 'Your '. ucfirst($lam->type).' has been approved by ' . Auth::user()->first_name . ' ' . Auth::user()->last_name,
            'link' => url('/') . '/api/admin/advance-and-claims/' . $lam->id,
            'user_type' => 'staff',
            'staff_id' => $lam->staff_id,
            'company_id' => $this->sessionDetails('company_id')
        ];
        
        $this->notify($data);

        LogService::admin(auth()->user()->id, $lam->staff->user_id, 'Approved an advance and claims ('. $lam->id . ')', 'UPDATE');
        return response()->json(['message' => 'Approved successfully', 'data' => $lam], 200);
    }

    public function declineAdvanceAndClaims($id)
    {
        $lam = AdvanceAndClaims::find($id);
        $lam->update(['status' => 'declined']);
        $data = [
            'title' => ucfirst($lam->type) . ' Request Declined!',
            'content' => 'Your ' . ucfirst($lam->type) . ' has been declined by ' . Auth::user()->first_name . ' ' . Auth::user()->last_name,
            'link' => url('/') . '/api/admin/advance-and-claims/' . $lam->id,
            'user_type' => 'staff',
            'staff_id' => $lam->staff_id,
            'company_id' => $this->sessionDetails('company_id')
        ];

        $this->notify($data);

        LogService::admin(auth()->user()->id, $lam->staff->user_id, 'Decline an advance and claims ('. $lam->id . ')', 'UPDATE');
        return response()->json(['message' => 'Declined successfully', 'data' => $lam], 200);
    }

    public function updateAdvanceAndClaimsSetting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:leave,absence',
            'period' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $company_id = $this->sessionDetails('company_id');

        $setting = AdvanceAndClaimsSetting::updateOrCreate(['company_id' => $company_id, 'type' => $request->type], [
            'type' => $request->type,
            'period' => $request->period
        ]);

        LogService::admin(auth()->user()->id, NULL, 'Create or update an advance and claims settings ('. $setting->id . ')', 'UPDATE');
        return response()->json(['message' => 'Updated successfully', 'data' => null], 200);
    }

    // public function getLeaveAndAbsenceSetting(Request $request)
    // {
    //     $settings = LeaveAndAbsenceSetting::where('company_id', $this->sessionDetails('company_id'));

    //     if (!empty($request->type)) {
    //         $settings = $settings->where('type', $request->type)->first();
    //     return response()->json(['message' => 'Retrieved successfully', 'data' => $settings], 200);

    //     }

    //     return response()->json(['message' => 'Retrieved successfully', 'data' => $settings->get()], 200);
    // }

    /**
     * Display the specified resource.
     */
    public function show(AdvanceAndClaims $advance_and_claims)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdvanceAndClaims $advance_and_claims)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AdvanceAndClaims $advance_and_claims)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdvanceAndClaims $advance_and_claims)
    {
        //
    }
}
