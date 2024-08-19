<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Estate;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = City::where('company_id', $this->sessionDetails('company_id'))->orderBy('created_at', 'desc')->get();
        return response()->json(['message' => 'Success', 'data' => $cities], 200);
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
            'name' => [
                'required', Rule::unique('cities')->where(function ($query) {
                    $query->where('company_id', $this->sessionDetails('company_id'));
                })
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $city = City::updateOrcreate([
            'name' => $request->name,
            'company_id' => $this->sessionDetails('company_id'),
        ]);
        
        LogService::admin(auth()->user()->id, NULL, 'Create a city ('. $city->id .')', 'CREATE');
        return response()->json(['message' => 'Created successfully', 'data' => $city], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        return response()->json(['message' => 'Success', 'data' => $city], 200);
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
            ], 412);
        }

        $city->update([
            'name' => $request->name,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        LogService::admin(auth()->user()->id, NULL, 'Update a city ('. $city->id .')', 'UPDATE');
        return response()->json(['message' => 'Updated Successfully.', 'data' => $city], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        if ($this->definedRelations('City', $city) > 0) {
            return response()->json(['message' => 'Unable to delete, resource in use', 'data' => []], 412);
        }
        
        $count = Estate::where(['city_id' => $city->id, 'company_id' => $city->company_id])->count();

        if ($count > 0) {
            return response()->json(['message' => 'Unable to delete, resource in use', 'data' => null], 412);
        }

        

        LogService::admin(auth()->user()->id, NULL, 'Delete a city ('. $city->id .')', 'DELETE');

        $city->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }
}
