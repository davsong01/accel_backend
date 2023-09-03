<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ContractCustomer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class ContractCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($customer_id)
    {
        $contracts = ContractCustomer::where('customer_id', $customer_id)->where('company_id', $this->sessionDetails('company_id'))->get();
        
        return response()->json(['message' => 'success', 'data' => $contracts], 200);

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
            'city_id' => 'required|exists:cities,id,company_id,' . $this->sessionDetails('company_id'),
            'location_id' => 'required|exists:locations,id,company_id,' . $this->sessionDetails('company_id'),
            'estate_id' => 'required|exists:estates,id,company_id,' . $this->sessionDetails('company_id'),
            'property_type' => 'required|in:land,building,both',
            'plot_size' => 'nullable',
            'building_type' => 'nullable',
            'no_of_units' => 'required',
            'contract_type' => 'required',
            'customer_id' => 'required',
            'subscribers' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $data = $validator->valid();
        $range = range(1, $data['no_of_units']);
        $data['subscribers'] = json_encode($data['subscribers']);
        $data = Arr::except($data, 'no_of_units');
       
        // Add contract
        foreach($range as $units){
            $contract = ContractCustomer::Create([
                'customer_id' => $data['customer_id'],
                'city_id' => $data['city_id'],
                'location_id' => $data['location_id'],
                'estate_id' => $data['estate_id'],
                'plot_size' => $data['plot_size'] ?? null,
                'building_type' => $data['building_type'] ?? null,
                'property_type' => $data['property_type'],
                'subscribers' => $data['subscribers'],
                'status' => 'lead',
                'contract_type' => $data['contract_type'],
                'company_id' => $this->sessionDetails('company_id'),
            ]);
        }

        return response()->json(['message' => 'success', 'data' => $contract], 200);

       
    }

    /**
     * Display the specified resource.
     */
    public function show(ContractCustomer $contractCustomer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContractCustomer $contractCustomer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContractCustomer $contractCustomer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContractCustomer $contractCustomer)
    {
        //
    }
}
