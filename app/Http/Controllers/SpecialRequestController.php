<?php

namespace App\Http\Controllers;

use App\Models\SpecialRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SpecialRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $special_requests = SpecialRequest::with(['location:id,name', 'city:id,name', 'estate_property_type'])
            ->where('company_id', $this->sessionDetails('company_id'))
            // ->where('branch_id', auth()->user()->staff->branch_id)
            ->orderBy('created_at', 'DESC')->paginate(20)->withQueryString();

        return response()->json(['message' => 'success', 'data' => $special_requests], 200);
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
    public function show(SpecialRequest $specialRequest): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SpecialRequest $specialRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SpecialRequest $specialRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SpecialRequest $specialRequest)
    {
        //
    }
}
