<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\EstatePropertyTypeDetails;

class PropertyTypeDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $property_types = EstatePropertyTypeDetails::with('plot_details:id,size', 'building_details:id,name')->where('company_id', $this->sessionDetails('company_id'))->orderBy('created_at','DESC');
       
        if (!empty($request->estate)) {
            $property_types = $property_types->where('estate_id', $request->estate);
        }

        if(!empty($request->type)){
            $property_types = $property_types->where('type',$request->type);
        }

        return response()->json(['message' => 'success', 'data' => $property_types->get()], 200);
       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PropertyTypeDetails $propertyTypeDetails): Response
    {
    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PropertyTypeDetails $propertyTypeDetails): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PropertyTypeDetails $propertyTypeDetails): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PropertyTypeDetails $propertyTypeDetails): RedirectResponse
    {
        //
    }
}
