<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = City::where('company_id', $this->sessionDetails('company_id'))->get();
        return response()->json(['message' => 'success', 'data' => $cities],200);
    }

    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 422);
        }

        $city = City::updateOrcreate([
            'name' => $request->name,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'City Added Succesfully', 'data' => $city], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, City $city)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, City $city)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 422);
        }

        $city->update([
            'name' => $request->name,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Update Successful', 'data' => $city], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        $city->delete();
        return response()->json(['message' => 'Delete Successful', 'data' => null], 200);
    }
}
