<?php

namespace App\Http\Controllers;

use App\Models\Estate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class EstateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estates = Estate::with(['location:id,name','city:id,name'])
        ->where('company_id', $this->sessionDetails('company_id'))
        // ->where('branch_id', auth()->user()->admin->branch_id)
        ->get();

        return response()->json(['message' => 'success', 'data' => $estates], 200);
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
            'name' => 'required',
            'city' => 'required|exists:cities,id',
            'location' => 'required|exists:locations,id',
            'property_type' => 'required|in:land,house,both',
        ]);
       
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 422);
        }
       
        $estate = Estate::updateOrcreate([
            'name' => $request->name,
            'city_id' => $request->city,
            'location_id' => $request->location,
            'property_type' => $request->property_type,
            'company_id' => $this->sessionDetails('company_id'),
            'branch_id' => $this->sessionDetails('branch_id'),
        ]);

        return response()->json(['message' => 'Estate Added Succesfully', 'data' => $estate], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Estate $estate)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Estate $estate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Estate $estate)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'city' => 'required|exists:cities,id',
            'location' => 'required|exists:locations,id',
            'property_type' => 'required|in:land,house,both',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 422);
        }

        $estate->update([
            'name' => $request->name,
            'city_id' => $request->city,
            'location_id' => $request->location,
            'property_type' => $request->property_type,
            'company_id' => $this->sessionDetails('company_id'),
            'branch_id' => $this->sessionDetails('branch_id'),
        ]);

        return response()->json(['message' => 'Update Successful', 'data' => $estate], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estate $estate)
    {
        $estate->delete();
        return response()->json(['message' => 'Delete Successful', 'data' => null], 200);
    }
}
