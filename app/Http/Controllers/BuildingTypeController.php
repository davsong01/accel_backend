<?php

namespace App\Http\Controllers;

use App\Models\BuildingType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Imports\BuildingTypeImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;


class BuildingTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $building_types = BuildingType::where('company_id', $this->sessionDetails('company_id'))->orderBy('created_at', 'desc')->get();
        return response()->json(['message' => 'success', 'data' => $building_types], 200);
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
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 422);
        }

        $building_type = BuildingType::updateOrcreate([
            'name' => $request->name,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Created successfully', 'data' => $building_type], 200);
    }

    public function importBulkBuildingType(Request $request)
    {
        $this->validate(request(), [
            'file' => 'required|
				mimetypes:xlsv,xlsx,xls,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,
				application/excel,application/x-excel,application/x-msexcel,text/comma-seperated-values, text/csv'
        ], [
            'file.mimetypes' => 'The file must be a file of type: xlsx'
        ]);

        Excel::import(new BuildingTypeImport(), request()->file('file'));

        return response()->json(['message' => 'Building Type Added Succesfully', 'data' => null], 200);
    }

    public function sampleBulkBuildingType()
    {
        $realpath = base_path() . '/sampleimports/buildingtype.xlsx';
        return response()->download($realpath);
    }


    /**
     * Display the specified resource.
     */
    public function show(BuildingType $buildingType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BuildingType $buildingType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BuildingType $buildingType)
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

        $buildingType->update([
            'name' => $request->name,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Updated Successfully.', 'data' => $buildingType], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BuildingType $buildingType)
    {
        $buildingType->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }
}
