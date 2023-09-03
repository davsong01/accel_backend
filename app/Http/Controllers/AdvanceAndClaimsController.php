<?php

namespace App\Http\Controllers;

use App\Models\AdvanceAndClaims;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        if (isset($id) && !empty($id)) {
            $lam = $lam->where('id', $id)->first();
            if ($lam) {
                return response()->json(['message' => 'Retrieved Successfully', 'data' => $lam], 200);
            } else {
                return response()->json(['message' => 'No records found', 'data' => null], 200);
            }
        }

        $lam = $lam->orderBy('created_at', 'DESC')->paginate(20);

        if ($lam->count() > 0) {
            return response()->json(['message' => 'Retrieved Successfully', 'data' => $lam], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => null], 200);
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
        //
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
