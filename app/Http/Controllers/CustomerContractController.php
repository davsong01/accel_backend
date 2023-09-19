<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
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
            // 'customer_id' => 'required|exists:customers,id,company_id,' . $this->sessionDetails('company_id'),
            'subscribers' => 'nullable',
            
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required|in:male,female',
            'email' => [
                'required', 'email', Rule::unique('users')->where(function ($query) {
                    $query->where('company_id', $this->sessionDetails('company_id'));
                })
            ],
            'phone_number' => 'required',
            'residential_address' => 'required',
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

        // Add user
        $user = $this->createUser($data);

        // Add customer
        if($user) {
            $customer = $this->createCustomer($user);
        }else{
            return response()->json(['message' => 'Could not create user', 'data' => null], 422);
        }
        
        if($customer) {
            // Add contract
            foreach ($range as $units) {
            $data['customer_id'] = $customer->id;
                $contract = $this->createCustomerContract($data);
            }
        }else{
            return response()->json(['message' => 'Could not create customer', 'data' => null], 422);
        }

        return response()->json(['message' => 'success', 'data' => null], 200);
    }

    public function createUser($request){
        // Add User
        $user = User::updateOrCreate([
            'first_name' => $request['first_name'],
            'email' => $request['email'],
            'gender' => $request['gender'],
            'last_name' => $request['last_name'],
            'phone_number' => $request['phone_number'],
            'residential_address' => $request['residential_address'],
            'company_id' => $this->sessionDetails('company_id'),
            'branch_id' => $this->sessionDetails('branch_id'),
            'email_verified_at' => now(),
            'status' => 'active',
            'type' => 'customer',
            'company_id' => $this->sessionDetails('company_id'),
            'branch_id' => $this->sessionDetails('branch_id'),
        ]);

        return $user;
    }

    public function createCustomer($user){
        $customer = Customer::updateOrCreate([
            'company_id' => $this->sessionDetails('company_id'),
            'branch_id' => $this->sessionDetails('branch_id'),
            'user_id' => $user->id,
            'verified' => 1,
        ]);

        return $customer;
    }

    public function createCustomerContract($data){
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

        return $contract;
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
    public function makeFormPayment(Request $request){

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
