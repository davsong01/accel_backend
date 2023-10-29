<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Models\AdvanceAndClaims;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class AdvanceAndClaimsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id = null)
    {
        $lam = AdvanceAndClaims::where('company_id', $this->sessionDetails('company_id'));
        
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

    public function staffAdvanceAndClaims(Request $request, $staff_id){
        $lam = AdvanceAndClaims::where('company_id', $this->sessionDetails('company_id'))->where('staff_id',$staff_id);
       
        if (isset($request->type) && !empty($request->type)) {
            $lam = $lam->where('type', $request->type);
        }
        if (isset($id) && !empty($id)) {
            $lam = $lam->where('id', $id)->first();
            if ($lam) {
                return response()->json(['message' => 'Retrieved Successfully', 'data' => $lam], 200);
            } else {
                return response()->json(['message' => 'No records found', 'data' => $lam], 200);
            }
        }

        $lam = $lam->orderBy('created_at', 'DESC')->paginate(20);

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
                'amount' => 'required',
                'time_sensitivity' => 'required',
            ]);
        }

        if ($request->type == 'claims') {
            $validator = Validator::make($request->all(), [
                'amount' => 'required',
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

        AdvanceAndClaims::create([
            'type' => $request->type,
            'purpose' => $request->purpose,
            'staff_id' => Auth::user()->staff->id, 
            'period' => $request->period ?? null,
            'date' => $request->date ?? null,
            'amount' => $request->amount,
            'purpose' => $request->purpose,
            'time_sensitivity' => $request->amount,
            'proof' => json_encode($proof),
            'status' => $status ?? 'pending',
            'company_id' => $this->sessionDetails('company_id')
        ]);

        return response()->json(['message' => 'Created successfully', 'data' => null], 200);

    }

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
