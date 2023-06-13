<?php

namespace App\Http\Controllers;

use App\Models\PlotSize;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class PlotSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plot = PlotSize::where('company_id', $this->sessionDetails('company_id'))->get();
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
            ], 422);
        }

        $plot = PlotSize::updateOrcreate([
            'size' => $request->size,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Plot Size Added Succesfully', 'data' => $plot], 200);
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
            ], 422);
        }

        $plotsize->update([
            'size' => $request->size,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Update Successful', 'data' => $plotsize], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlotSize $plotsize)
    {
        $plotsize->delete();
        return response()->json(['message' => 'Delete Successful', 'data' => null], 200);
    }
}
