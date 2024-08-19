<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Estate;
use App\Models\Location;
use App\Services\LogService;
use Carbon\Carbon;
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
        $location = Location::with('city')->where('company_id', sessionDetails('company_id'))->orderBy('created_at', 'desc')->get();
        return response()->json(['message' => 'Success', 'data' => $location], 200);
    }

    public function cityLocations($city_id)
    {
        $locations = Location::where(['company_id' => sessionDetails('company_id'), 'city_id' => $city_id])->get();

        if (($locations->count() <= 0)) {
            return response()->json(['message' => 'No records found', 'data' => $locations], 200);
        }
        
        return response()->json(['message' => 'Success', 'data' => $locations], 200);
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
            ], 412);
        }

        $location = Location::updateOrcreate([
            'name' => $request->name,
            'city_id' => $request->city,
            'company_id' => sessionDetails('company_id'),
        ]);

        LogService::admin(auth()->user()->id, null, 'Create a location (' . $location->id  . ')' , 'UPDATE');
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
            ], 412);
        }

        $location->update([
            'name' => $request->name,
            'city_id' => $request->city,
            'company_id' => sessionDetails('company_id'),
        ]);

        LogService::admin(auth()->user()->id, null, 'Update a location (' . $location->id  . ')' , 'UPDATE');
        return response()->json(['message' => 'Updated Successfully.', 'data' => $location], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        $count = Estate::where(['location_id' => $location->id, 'company_id' => $location->company_id])->count();

        if ($count > 0) {
            return response()->json(['message' => 'Unable to delete, resource in use', 'data' => null], 412);
        }

        LogService::admin(auth()->user()->id, null, 'Delete a location (' . $location->id  . ')' , 'DELETE');
        
        $location->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }

    /**
     * Search resources in storage
     * 
     */
    public function search(Request $request)
    {
        $params = $request->only(['city_ids']);
        $company_id = sessionDetails('company_id');

        $query = Location::where('company_id', $company_id);

        // search by location name
        if($request->input('name') && $name = $request->input('name')){
            $query->where('name', 'LIKE', '%'. $name .'%');
        }

        // search by city ids
        if($request->input('city_ids') && $city_ids = explode(',', $request->input('city_ids'))){
            $query->whereIn('city_id', $city_ids);
        }

        $from = $request->input('from');
        $to = $request->input('to');

        // search by date range
        if($from && $to){
            $from = Carbon::parse($request->input('from'));

            $to = Carbon::parse($request->input('to'));
            if ($to->format('H:i:s') == '00:00:00')
                $to->endOfDay();

            $query->whereBetween('created_at', [$from, $to]);
        }

        // search by city names
        if($request->input('city_names') && $city_names = $request->input('city_names')){
            $cities = City::where('company_id', $company_id)->whereNotNull('company_id')
            ->where('name', 'LIKE', '%' . $city_names . '%')
            ->get();
            $city_ids = $cities->whereNotNull('id')->pluck('id')->toArray();

            $query->whereIn('city_id',  $city_ids);
        }

        if($request->input('page')){
            $locations = $query->paginate(20);
        } else {
            $locations = $query->get();
        }

        return response()->json(['message' => 'Success', 'data' => $locations], 200);
    }
}
