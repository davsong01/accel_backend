<?php

namespace App\Http\Controllers;

use App\Models\PlotSize;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Imports\PlotSizeImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use App\Models\EstatePropertyTypeDetails;
use Illuminate\Support\Facades\Validator;


class PlotSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plot = PlotSize::where('company_id', $this->sessionDetails('company_id'))->orderBy('created_at', 'desc')->get();

        return response()->json(['message' => 'success', 'data' => $plot], 200);
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
            'size' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $plot = PlotSize::updateOrcreate([
            'size' => $request->size,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Created successfully', 'data' => $plot], 200);
    }

    public function importBulkPlotSize(Request $request)
    {
        $this->validate(request(), [
            'file' => 'required|
				mimetypes:xlsv,xlsx,xls,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,
				application/excel,application/x-excel,application/x-msexcel,text/comma-seperated-values, text/csv'
        ], [
            'file.mimetypes' => 'The file must be a file of type: xlsx'
        ]);

        Excel::import(new PlotSizeImport(), request()->file('file'));

        return response()->json(['message' => 'Plot Sizes Added Succesfully', 'data' => null], 200);
    }

    public function sampleBulkPlotSize()
    {
        $realpath = base_path() . '/sampleimports/plotsize.xlsx';
        return response()->download($realpath);
    }

    /**
     * Display the specified resource.
     */
    public function show(PlotSize $plotsize)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlotSize $plotsize)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PlotSize $plotsize)
    {
        $validator = Validator::make($request->all(), [
            'size' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $plotsize->update([
            'size' => $request->size,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Updated Successfully.', 'data' => $plotsize], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlotSize $plotsize)
    {
        $count = EstatePropertyTypeDetails::where(['plot_size' => $plotsize->size, 'company_id' => $plotsize->company_id])->count();
        if ($count > 0) {
            return response()->json(['message' => 'Unable to delete, resource in use', 'data' => null], 412);
        }
        $plotsize->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }
}
