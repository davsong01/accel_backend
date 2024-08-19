<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerContract;
use App\Models\ContractAllocation;
use Illuminate\Support\Facades\Validator;

class ContractAllocationController extends Controller
{
    public function allocateContract(Request $request){
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id,company_id,' . $this->sessionDetails('company_id'),
            'contract_id' => 'required|exists:customer_contracts,id,company_id,' . $this->sessionDetails('company_id'),
            'plot_number' => 'nullable',
            'building_number' => 'nullable',
            'number_of_months' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => [],
            ], 412);
        }

        $contract = CustomerContract::where('id', $request->contract_id)->first();
        
        if($contract->contract_status != 'paid'){
            return response()->json([
                'error' => 'This contract is not yet paid for completely, please try again later',
                'data' => [],
            ], 412);
        }

        ContractAllocation::updateOrCreate([
            'customer_id' => $request->customer_id,
            'contract_id' => $request->contract_id,
            'plot_number' => $request->plot_number,
            'building_number' => $request->building_number,
            'number_of_months' => $request->number_of_months,
            'staff_id' => auth()->user()->staff->id,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        $contract->update(['contract_status' => 'completed']);
        // Send allocation mail
        $data['type'] = 'allocation.email';
        $data['name'] =  $contract->customer->user->first_name;
        $data['email'] =  $contract->customer->user->email;
        
        $this->logEmail($data);
        
        return response()->json(['message' => 'Allocation successful', 'data' => []], 200);

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ContractAllocation $contractAllocation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContractAllocation $contractAllocation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContractAllocation $contractAllocation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContractAllocation $contractAllocation)
    {
        //
    }
}
