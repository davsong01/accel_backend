<?php

namespace App\Http\Controllers;

use App\Models\Estate;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Imports\EstateImport;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use App\Models\EstatePropertyTypeDetails;
use Illuminate\Support\Facades\Validator;

class EstateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estates = Estate::with(['location:id,name','city:id,name', 'estate_property_type'])
        ->where('company_id', $this->sessionDetails('company_id'))
        // ->where('branch_id', auth()->user()->admin->branch_id)
        ->orderBy('created_at', 'DESC')->paginate(20);

        return response()->json(['message' => 'success', 'data' => $estates], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }
    
    public function filterPropertyCount($estate, $query=null){
        $property_count = EstatePropertyTypeDetails::where('estate_id', $estate);

        if($query == 'land'){
            $estates = $property_count->where('type','land');
        }

        if ($query == 'building') {
            $estates = $property_count->where('type','building');
        }

        $property_count = $property_count->count();
    
        return response()->json(['message' => 'success', 'data' => $property_count], 200);
        
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
            'property_type' => 'required|in:land,building,both',
            'project_supervisor' => 'sometimes|exists:admins,id',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }
        
        $estate = Estate::updateOrcreate([
            'name' => $request->name,
            'city_id' => $request->city,
            'location_id' => $request->location,
            'property_type' => $request->property_type,
            'project_supervisor' => $request->project_supervisor,
            'bypass_approval' => $request->bypass_approval,
            'description' => $request->description,
            'company_id' => $this->sessionDetails('company_id'),
            'branch_id' => $this->sessionDetails('branch_id'),
        ]);

        // Create Property Type Details
        if($request->has('building_details') && !empty($request->building_details)){
            foreach($request->building_details as $details){
                EstatePropertyTypeDetails::updateOrcreate([
                    'estate_id' => $estate->id,
                    'type' => 'building',
                    "plot_size" => $details['plot_size'] ?? null,
                    "building_type" => $details['building_type'] ?? null,
                    "estate_price" => $details['estate_price'],
                    "settings_and_excavation" => $details['settings_and_excavation'] ?? null,
                    "infrastructural_fee" => $details['infrastructural_fee'] ?? null,
                    "company_id" => $this->sessionDetails('company_id') ?? null
                ]);
            }
        }
        if ($request->has('land_details') && !empty($request->land_details)) {
            foreach ($request->land_details as $details) {
                EstatePropertyTypeDetails::updateOrcreate([
                    'estate_id' => $estate->id,
                    'type' => 'land',
                    "plot_size" => $details['plot_size'] ?? null,
                    "building_type" => $details['building_type'] ?? null,
                    "estate_price" => $details['estate_price'],
                    "settings_and_excavation" => $details['settings_and_excavation'] ?? null,
                    "infrastructural_fee" => $details['infrastructural_fee'] ?? null,
                    "company_id" => $this->sessionDetails('company_id') ?? null
                ]);
            }
        }

        return response()->json(['message' => 'Estate Added Succesfully', 'data' => $estate], 200);
    }

    public function addEstatePropertyType(Request $request){
        $validator = Validator::make($request->all(), [
            "estate_id" => 'required|exists:estates,id',
            "type" => "required|in:land,building,both",
            "estate_price" => "sometimes",
            "plot_size" => "sometimes",
            "estate_price" => "sometimes",
            "settings_and_excavation" => "sometimes",
            "infrastructural_fee" => "sometimes"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $p_type = EstatePropertyTypeDetails::updateOrcreate([
            'estate_id' => $request->estate_id,
            'type' => $request->type,
            "plot_size" => $request->plot_size ?? null,
            "building_type" => $request->building_type ?? null,
            "estate_price" => $request->estate_price ?? null,
            "settings_and_excavation" => $request->settings_and_excavation ?? null,
            "infrastructural_fee" => $request->infrastructural_fee ?? null,
            "company_id" => $this->sessionDetails('company_id') ?? null

        ]);

        return response()->json(['message' => 'Estate Property Type added Succesfully', 'data' => $p_type], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Estate $estate)
    {
        $estate = $estate->load('city', 'location','estate_property_type');
        $estate->project_supervisor_name = ($estate->admin->user->first_name ?? '') .' '. ($estate->admin->user->last_name ?? '');
        
        return response()->json(['message' => 'success', 'data' => $estate], 200);
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
            'bypass_approval' => 'required'
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
            'project_supervisor' => $request->project_supervisor,
            'branch_id' => $this->sessionDetails('branch_id'),
            'bypass_approval' => $request->bypass_approval,
            'description' => $request->description,
        ]);

        if ($request->has('building_details') && !empty($request->building_details)) {
            foreach ($request->building_details as $details) {
                EstatePropertyTypeDetails::updateOrcreate([
                    'estate_id' => $estate->id,
                    'type' => 'building',
                    "plot_size" => $details['plot_size'],
                    "building_type" => $details['building_type'],
                    "estate_price" => $details['estate_price'],
                    "settings_and_excavation" => $details['settings_and_excavation'],
                    "infrastructural_fee" => $details['infrastructural_fee']
                ]);
            }
        }
        if ($request->has('land_details') && !empty($request->land_details)) {
            foreach ($request->land_details as $details) {
                EstatePropertyTypeDetails::updateOrcreate([
                    'estate_id' => $estate->id,
                    'type' => 'land',
                    "plot_size" => $details['plot_size'],
                    "building_type" => $details['building_type'],
                    "estate_price" => $details['estate_price'],
                    "settings_and_excavation" => $details['settings_and_excavation'],
                    "infrastructural_fee" => $details['infrastructural_fee'],
                    
                ]);
            }
        }
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

    public function updateEstatePropertyType(Request $request, $id){
        $property_type = EstatePropertyTypeDetails::find($id);

        $property_type->update([
            "plot_size" => $request->plot_size,
            "building_type" => $request->building_type,
            "estate_price" => $request->estate_price,
            "settings_and_excavation" => $request->settings_and_excavation,
            "infrastructural_fee" => $request->infrastructural_fee
        ]);

        return response()->json(['message' => 'Update Successful', 'data' => $property_type], 200);
    }

    public function changeEstatePropertyTypeStatus(Request $request, $id)
    {
        $property_type = EstatePropertyTypeDetails::find($id);
        if($property_type->status == 1){
            $property_type->update(['status'=>0]);
        }else{
            $property_type->update(['status' => 1]);
        }

        return response()->json(['message' => 'Update Successful', 'data' => $property_type], 200);
    }

    public function changeEstateStatus($id){
        $estate = Estate::find($id);
        if ($estate->status == 1) {
            $estate->update(['status' => 0]);
        } else {
            $estate->update(['status' => 1]);
        }

        return response()->json(['message' => 'Update Successful', 'data' => $estate], 200);
    }

    public function deleteEstatePropertyType($id)
    {
        $estate = EstatePropertyTypeDetails::find($id);
        $estate->delete();
        
        return response()->json(['message' => 'Delete Successful', 'data' => null], 200);
    }

    public function importBulkEstates(Request $request)
    {
        $this->validate(request(), [
            'file' => 'required|
				mimetypes:xlsv,xlsx,xls,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,
				application/excel,application/x-excel,application/x-msexcel,text/comma-seperated-values, text/csv'
        ], [
            'file.mimetypes' => 'The file must be a file of type: xlsx'
        ]);

        try {
            //code...
            Excel::import(new EstateImport(), request()->file('file'));
        } catch (\Throwable $th) {
            return response()->json(['errors'=>$th->getMessage()],422);
        }

        return response()->json(['message' => 'Estates Added Succesfully', 'data' => null], 200);

    }

    public function sampleBulkEstate()
    {
        $realpath = base_path() . '/sampleimports/sample_estate.xlsx';
        return response()->download($realpath);
    }

}
