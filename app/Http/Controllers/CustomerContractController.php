<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Payment;
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
        $contracts = CustomerContract::with('property_type_details:type,id,estate_price,settings_and_excavation,infrastructural_fee', 'city:name,id', 'estate:name,id', 'location:name,id', 'building_type:id,name', 'plot_size:id,size')->where('customer_id', $customer_id)->where('company_id', $this->sessionDetails('company_id'))->select(['id', 'property_type_id', 'customer_id', 'city_id', 'location_id', 'estate_id', 'property_type', 'status', 'plot_size', 'building_type', 'subscribers', 'contract_type', 'property_type','created_at'])
            // ->groupBy('estate_id')
            ->orderBy('created_at', 'DESC')
            ->get();


        $allContracts = [];
        if ($contracts) {
            // get the customer
            $customer = Customer::withCount(['singleContract', 'jointContract', 'contracts'])->where('id', $customer_id)->where('company_id', $this->sessionDetails('company_id'))->first();

            $customerX = $customer;
            $customerY = $customer;

            $dataX = [
                'all_contracts' => $customer->contracts_count,
                'joint_contracts' => $customerX->joint_contract_count,
                'single_contracts' => $customerY->single_contract_count
            ];
            $data['contract_count'] = $dataX;
      
            foreach ($contracts as $key => $contract) {
                // $estate->load('city', 'location', 'estate_property_type')
                $contract->subscribers = !empty($contract->subscribers) ? json_decode($contract->subscribers) : '';
                $contract['location'] = $contract->location->name;
                $allContracts[$key] = $contract;
                $allContracts[$key]['form_amount'] = $this->getContractFormAmount($contract, 'single');
            }
            $data['all_contracts'] = $allContracts;

            return response()->json(['message' => 'success', 'data' => $data], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => []], 200);
        }
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
        if (!empty($request->customer_id)) {
            $validator = Validator::make($request->all(), [
                'city_id' => 'required|exists:cities,id,company_id,' . $this->sessionDetails('company_id'),
                'location_id' => 'required|exists:locations,id,company_id,' . $this->sessionDetails('company_id'),
                'estate_id' => 'required|exists:estates,id,company_id,' . $this->sessionDetails('company_id'),
                'property_type_id' => [
                    'required', Rule::exists('estate_property_type_details', 'id')->where(function ($query) use ($request) {
                        $query->where('company_id', $this->sessionDetails('company_id'))->where('estate_id', $request->estate_id)->where('type', $request->property_type);
                    })
                ],
                'property_type' => 'required|in:land,building',
                'plot_size' => 'nullable|exists:plot_sizes,id',
                'building_type' => 'nullable|exists:building_types,id,company_id,' . $this->sessionDetails('company_id'),
                'no_of_units' => 'required',
                'contract_type' => 'required',
                'customer_id' => 'required|exists:customers,id,company_id,' . $this->sessionDetails('company_id'),
                'subscribers' => 'nullable',
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
                $data['customer_id'] = $data['customer_id'];
                $this->createCustomerContract($data);
            }

            return response()->json(['message' => 'success', 'data' => ['customer_id' => $data['customer_id']]], 200);
        } else {
            $validator = Validator::make($request->all(), [
                'city_id' => 'required|exists:cities,id,company_id,' . $this->sessionDetails('company_id'),
                'location_id' => 'required|exists:locations,id,company_id,' . $this->sessionDetails('company_id'),
                'estate_id' => 'required|exists:estates,id,company_id,' . $this->sessionDetails('company_id'),
                'property_type' => 'nullable',
                'property_type_id' => [
                    'required', Rule::exists('estate_property_type_details', 'id')->where(function ($query) use ($request) {
                        $query->where('company_id', $this->sessionDetails('company_id'))->where('estate_id', $request->estate_id);
                    })
                ],
                'plot_size' => 'nullable|exists:plot_sizes,id',
                'building_type' => 'nullable|exists:building_types,id',
                'no_of_units' => 'required',
                'contract_type' => 'required',
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
            if ($user) {
                $customer = $this->createCustomer($user);
            } else {
                return response()->json(['message' => 'Could not create user', 'data' => null], 422);
            }

            if ($customer) {
                // Add contract
                foreach ($range as $units) {
                    $data['customer_id'] = $customer->id;
                    $contract = $this->createCustomerContract($data);
                }
            } else {
                return response()->json(['message' => 'Could not create customer', 'data' => null], 422);
            }

            return response()->json(['message' => 'success', 'data' => ['customer_id' => $customer->id]], 200);
        }

        return response()->json(['message' => 'success', 'data' => null], 200);
    }

    public function createUser($request)
    {
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

    public function createCustomer($user)
    {
        $customer = Customer::updateOrCreate([
            'company_id' => $this->sessionDetails('company_id'),
            'branch_id' => $this->sessionDetails('branch_id'),
            'user_id' => $user->id,
            'verified' => 1,
        ]);

        return $customer;
    }

    public function createCustomerContract($data)
    {
        $contract = CustomerContract::Create([
            'customer_id' => $data['customer_id'],
            'city_id' => $data['city_id'],
            'location_id' => $data['location_id'],
            'estate_id' => $data['estate_id'],
            'plot_size' => $data['plot_size'] ?? null,
            'building_type' => $data['building_type'] ?? null,
            'property_type' => $data['property_type'],
            'property_type_id' => $data['property_type_id'],
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
        $contract = CustomerContract::with('property_type_details:type,id,estate_price,settings_and_excavation,infrastructural_fee','city:name,id', 'estate:name,id', 'location:name,id', 'building_type:id,name', 'plot_size:id,size')->where('id', $contract_id)->select(['id', 'property_type_id','customer_id', 'city_id', 'location_id', 'estate_id', 'property_type', 'status', 'plot_size','building_type', 'subscribers','contract_type', 'property_type'])->orderBy('created_at', 'DESC')->where('company_id', $this->sessionDetails('company_id'))->first();

        if($contract){
            $contract->subscribers = !empty($contract->subscribers) ? json_decode($contract->subscribers) : '';
            return response()->json(['message' => 'success', 'data' => $contract], 200);
        }

        return response()->json(['message' => 'No record found', 'data' => null], 200);
        
    }

    public function makeFormPayment(Request $request)
    {
        // $request['contract_ids'] = [31,30,29,26];
        $validator = Validator::make($request->all(), [
            'contract_ids' => 'required',
            'account_id' => 'required|exists:accounts,id',
            'payment_slip' => 'nullable',
            'description' => 'nullable',
            'amount' => 'required',
            'customer_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $data = $validator->valid();

        $request['contract_ids'] = json_decode($request['contract_ids'], true);
        $contract_ids = array_column($request['contract_ids'], 'contract_id');

        $ids = [];
        array_walk_recursive($contract_ids, function ($a) use (&$ids) {
            $ids[] = $a;
        });

    
        $form_payment = $this->generateFormPayment($ids, $request->customer_id);
        
        if ($request->amount != $form_payment['form_payment']) {
            return response()->json([
                'error' => 'Amount does not correspond with selected contract. Amount should be exactly ' . $form_payment['form_payment'],
                'data' => null,
            ], 412);
        }

        if (!empty($request->payment_slip)) {
            $filename = $this->uploadFile($request->payment_slip, 'payments');
        }
       
        if ($form_payment) {
            foreach ($form_payment['explanation'] as $key => $form) {
                $customer_contract_ids = implode(',', $form['contracts']);
                              
                Payment::updateOrCreate(['customer_contract_ids' => $customer_contract_ids], [
                    'customer_contract_ids' => $customer_contract_ids,
                    'account_id' => $data['account_id'],
                    'purpose' => 'form_payment',
                    'payment_slip' => $filename ?? null,
                    'description' => $data['description'],
                    'amount' => $form['form_amount'],
                    'estate_id' => $key,
                    'customer_id' => $data['customer_id'],
                    'company_id' => $this->sessionDetails('company_id'),
                ]);

                foreach ($form['contracts'] as $contract) {
                    CustomerContract::where('id', $contract)->update(['status' => 'prospect']);
                }  
            }
        }
        // change contract to prospect
        return response()->json(['message' => 'success', 'data' => null], 200);
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
        $validator = Validator::make($request->all(), [
            'city_id' => 'required|exists:cities,id,company_id,' . $this->sessionDetails('company_id'),
            'location_id' => 'required|exists:locations,id,company_id,' . $this->sessionDetails('company_id'),
            'estate_id' => 'required|exists:estates,id,company_id,' . $this->sessionDetails('company_id'),
            'property_type_id' => [
                'required', Rule::exists('estate_property_type_details', 'id')->where(function ($query) use ($request) {
                    $query->where('company_id', $this->sessionDetails('company_id'))->where('estate_id', $request->estate_id)->where('type', $request->property_type);
                })
            ],
            'property_type' => 'required|in:land,building',
            'plot_size' => 'nullable|exists:plot_sizes,id',
            'building_type' => 'nullable|exists:building_types,id,company_id,' . $this->sessionDetails('company_id'),
            'contract_type' => 'required',
            'customer_id' => 'required|exists:customers,id,company_id,' . $this->sessionDetails('company_id'),
            'subscribers' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $CustomerContract->update([
            'customer_id' => $request->customer_id,
            'city_id' => $request->city_id,
            'location_id' => $request->location_id,
            'estate_id' => $request->estate_id,
            'plot_size' => $request->plot_size,
            'building_type' => $request->building_type,
            'property_type' => $request->property_type,
            'property_type_id' => $request->property_type_id,
            'subscribers' => $request->has('subscribers') ? $request->subscribers : $CustomerContract->subscribers,
            'status' => 'lead',
            'contract_type' => $request->contract_type,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        return response()->json(['message' => 'Succesfully updated', 'data' => ['contract_id' => $CustomerContract->id, 'customer_id' => $request->customer_id]], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerContract $CustomerContract, $id)
    {
        $contract = CustomerContract::where('id', $id)->first();
        
        if(!$contract){
            return response()->json(['message' => 'No records found', 'data' => null], 200);
        }

        $customer_id = $contract->customer_id;
        $contract->delete();
        return response()->json(['message' => 'Succesfully deleted', 'data' => ['customer_id' => $customer_id]], 200);

    }

    public function singleCustomerFormDetails(Request $request, $customer_id)
    {
        
        $estates = CustomerContract::distinct('estate_id')->with('estate:id,name,form_amount')->select('estate_id')->where('customer_id', $customer_id)->where('company_id', $this->sessionDetails('company_id'));

        if(!empty($request->status)){
            $estates = $estates->where('status',$request->status);
        }

        $estates = $estates->get();

        $customer = Customer::with('user:id,first_name,last_name,middle_name')->where('id', $customer_id)->first();
        $customerDetails = $customer->user ?? null;
        
        $contracts = CustomerContract::with('location:name,id')->where('customer_id', $customer_id)->where('company_id', $this->sessionDetails('company_id'))->select(['id', 'customer_id', 'city_id', 'location_id', 'estate_id', 'property_type', 'status', 'plot_size', 'building_type', 'contract_type', 'property_type'])->orderBy('created_at', 'DESC');

        if (!empty($request->status)) {
            $contracts = $contracts->where('status', $request->status);
        }

        $estates = $estates->map(function ($query) {
            $query['estate_name'] = $query->estate->name;
            $query['form_amount'] = $query->estate->form_amount;
            return $query;
        });
        $data = [];
        $allContracts = [];
        if ($contracts) {
            // get the customer
            $customer = Customer::withCount(['singleContract', 'jointContract', 'contracts'])->where('id', $customer_id)->where('company_id', $this->sessionDetails('company_id'))->first();
            $customerX = $customer;
            $customerY = $customer;
           
            $dataX = [
                'all_contracts' => $customer->contracts_count,
                'joint_contracts' => $customerX->joint_contract_count,
                'single_contracts' => $customerY->single_contract_count
            ];
            $data['contract_count'] = $dataX;

            foreach ($estates as $estate) {
                // foreach ($contracts as $key => $contract) {
                $details = CustomerContract::with('location:name,id', 'building_type:id,name', 'plot_size:id,size')->where('estate_id', $estate->estate_id)->where('customer_id', $customer_id)->select(['id', 'customer_id', 'city_id', 'location_id', 'estate_id', 'property_type', 'status', 'plot_size', 'building_type', 'contract_type', 'property_type'])->orderBy('created_at', 'DESC')->where('company_id', $this->sessionDetails('company_id'));

                if (!empty($request->status)) {
                    $details = $details->where('status', $request->status);
                }

                $details = $details->get();

                $allContracts[] = [
                    'estate_id' => $estate->estate_id,
                    'estate_name' => $estate->estate_name,
                    'form_amount' => $estate->form_amount,
                    'estate_name' => $estate->estate_name,
                    'contracts' => $details,
                ];

                // foreach ($details as $detail) {
                    
                // }
            }
       

            $data['customer_details'] = [
                'id' => $customer_id,
                'first_name' => $customerDetails->first_name,
                'last_name' => $customerDetails->last_name,
                'middle_name' => $customerDetails->middle_name
            ];

            $data['all_contracts'] = $allContracts;
            return response()->json(['message' => 'success', 'data' => $data], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => []], 200);
        }
    }

    public function formPaymentHistory($customer_id)
    {
        $histories = Payment::with('estate:id,name')->where(['customer_id' => $customer_id, 'company_id' => $this->sessionDetails('company_id'), 'purpose' => 'form_payment'])->orderBy('created_at', 'DESC')->get();

        $data = [];

        foreach ($histories as $history) {
            $contracts = [];
            $h_contracts = !empty($history->customer_contract_ids) ? explode(',', $history->customer_contract_ids) : [];

            foreach ($h_contracts as $sub) {
                $cont = CustomerContract::with('building_type_details:id,name', 'plot_size_details:id,size')->where('id', $sub)->select('id', 'building_type', 'plot_size')->first();
                $contracts[] = [
                    'id' => $cont->id,
                    'plot_size' => $cont->plot_size_details,
                    'building_type' => $cont->building_type_details,
                ];
            }

            $data[$history->estate_id] = [
                'id' => $history->id,
                'customer_id' => $history->customer_id,
                'created_at' => $history->created_at,
                'amount' => $history->amount,
                'estate_id' => $history->estate_id,
                'estate_name' => $history->estate->name,
                'payment_slip' => $history->payment_slip,
                'purpose' => ucwords(str_replace('_', ' ', $history->purpose)),
                'contracts' => $contracts
            ];
        }

        return response()->json(['message' => 'success', 'data' => $data], 200);
    }

}
