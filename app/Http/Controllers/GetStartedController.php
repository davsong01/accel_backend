<?php

namespace App\Http\Controllers;

use App\Models\GetStarted;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GetStartedController extends Controller
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
            'full_name' => ['required', 'string', 'min:3'],
            'email' => ['required', 'email'],
            'company_name' => ['required', 'string', 'min:3', 'max:255'],
            'company_size' => ['required', 'in:0-10,11-30,31-60,60-above'],
            'industry' => ['required', 'in:real-estate-agencies,property-management-companies,construction-and-management,healthcare-facilities,hospitality-and-tourism,education-institutions,transportation-and-logistics,agriculture-and-farming,others'],
            'phone_number' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $attributes = $validator->validated();
        GetStarted::create($attributes);
        return response()->json(['message' => 'Form submitted successfully', 'data' => null], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(GetStarted $getStarted)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GetStarted $getStarted)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GetStarted $getStarted)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GetStarted $getStarted)
    {
        //
    }
}
