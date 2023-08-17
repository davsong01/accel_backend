<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\EstateConstructionSetting;
use Illuminate\Support\Facades\Validator;

class EstateConstructionSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches = EstateConstructionSetting::where('company_id', $this->sessionDetails('company_id'))->get();
        if ($branches->count() > 0) {
            return response()->json(['message' => 'Retrieved Successfully', 'data' => $branches], 200);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $setting = EstateConstructionSetting::updateOrcreate([
            'name' => $request->name,
            'price' => $request->price,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Created successfully', 'data' => $setting], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(EstateConstructionSetting $estateConstructionSetting)
    {
        return response()->json(['message' => 'success', 'data' => $estateConstructionSetting], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EstateConstructionSetting $estateConstructionSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EstateConstructionSetting $estateConstructionSetting)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 422);
        }

        $estateConstructionSetting->update([
            'name' => $request->name,
            'price' => $request->price,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Updated Successfully.', 'data' => $estateConstructionSetting], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EstateConstructionSetting $estateConstructionSetting)
    {
        $estateConstructionSetting->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }
}
