<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CustomerContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class CustomerContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($customer_id)
    {
        $contracts = CustomerContract::where('customer_id', $customer_id)->where('company_id', $this->sessionDetails('company_id'))->select(['id', 'customer_id', 'city_id', 'location_id', 'estate_id', 'property_type', 'status', 'plot_size', 'building_type', 'contract_type', 'property_type'])
        ->groupBy('estate_id')
            ->get();

        $allContracts = [];
        foreach ($contracts as $key => $contract) {
            $allContracts[$key]['contracts'] = $contract;
            $allContracts[$key]['form_amount'] = $this->getContractFormAmount($contract);
        }

        return response()->json(['message' => 'success', 'data' => $allContracts], 200);
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
            'plot_size' => 'nullable|exists:plot_sizes,id',
            'building_type' => 'nullable|exists:building_types,id',
            'no_of_units' => 'required',
            'contract_type' => 'required',
            'customer_id' => 'required|exists:customers,id,company_id,' . $this->sessionDetails('company_id'),
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
        foreach ($range as $units) {
            $contract = CustomerContract::Create([
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
    public function show($contract_id)
    {
        $contract = CustomerContract::whereId($contract_id)->first();
        $contract->form_amount = $this->getContractFormAmount($contract, 'single');

        return response()->json(['message' => 'success', 'data' => $contract], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerContract $CustomerContract)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerContract $CustomerContract)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerContract $CustomerContract)
    {
        //
    }
}
