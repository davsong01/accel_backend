<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $location = Location::with('city')->where('company_id', $this->sessionDetails('company_id'))->orderBy('created_at', 'desc')->get();
        return response()->json(['message' => 'success', 'data' => $location], 200);
    }

    public function cityLocations($city_id)
    {
        $locations = Location::where(['company_id' => $this->sessionDetails('company_id'), 'city_id' => $city_id])->get();

        if (($locations->count() <= 0)) {
            return response()->json(['message' => 'No records found', 'data' => null], 200);
        }

        return response()->json(['message' => 'success', 'data' => $locations], 200);
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
            'city' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 422);
        }

        $location = Location::updateOrcreate([
            'name' => $request->name,
            'city_id' => $request->city,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Created successfully', 'data' => $location], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Location $location)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'city' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 422);
        }

        $location->update([
            'name' => $request->name,
            'city_id' => $request->city,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Updated Successfully.', 'data' => $location], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $location->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }
}
