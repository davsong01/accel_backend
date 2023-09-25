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
        $contracts = CustomerContract::with('location:name,id', 'building_type:id,name')->where('customer_id', $customer_id)->where('company_id', $this->sessionDetails('company_id'))->select(['id', 'customer_id', 'city_id', 'location_id', 'estate_id', 'property_type', 'status', 'plot_size', 'building_type', 'contract_type', 'property_type'])
            // ->groupBy('estate_id')
            ->orderBy('created_at', 'DESC')
            ->get();


        // $contracts_count = $contracts->first()->customer->contracts->count();


        $allContracts = [];
        if ($contracts) {
            // get the customer
            $customer = Customer::withCount(['singleContract', 'joinContract', 'contracts'])->where('id', $customer_id)->where('company_id', $this->sessionDetails('company_id'))->first();
            $dataX = [
                'all_contracts' => $customer->contracts_count,
                'joint_contracts' => $customer->single_contract_count,
                'single_contracts' => $customer->join_contract_count
            ];
            $data['contract_count'] = $dataX;

            foreach ($contracts as $key => $contract) {
                // $estate->load('city', 'location', 'estate_property_type')
                $contract['location'] = $contract->location->name;
                $allContracts[$key] = $contract;
                $allContracts[$key]['form_amount'] = $this->getContractFormAmount($contract, 'single');
            }
            $data['all_contracts'] = $allContracts;

            return response()->json(['message' => 'success', 'data' => $data], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => null], 200);
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
                'property_type' => 'required|in:land,building,both',
                'plot_size' => 'nullable|exists:plot_sizes,id',
                'building_type' => 'nullable|exists:building_types,id',
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
                'property_type' => 'required|in:land,building,both',
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

    public function makeFormPayment(Request $request)
    {
        // $request['contract_ids'] = [31,30,29,26];
        $validator = Validator::make($request->all(), [
            'contract_ids' => 'required',
            'account_id' => 'required|exists:accounts,id',
            'payment_slip' => 'required',
            'description' => 'required',
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
        $form_payment = $this->generateFormPayment($request['contract_ids'], $request->customer_id);

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
            }
        }

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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerContract $CustomerContract)
    {
        //
    }

    public function singleCustomerFormDetails($customer_id)
    {
        $estates = CustomerContract::distinct('estate_id')->with('estate:id,name,form_amount')->select('estate_id')->where('customer_id', $customer_id)->where('company_id', $this->sessionDetails('company_id'))->get();

        $contracts = CustomerContract::with('location:name,id')->where('customer_id', $customer_id)->where('company_id', $this->sessionDetails('company_id'))->select(['id', 'customer_id', 'city_id', 'location_id', 'estate_id', 'property_type', 'status', 'plot_size', 'building_type', 'contract_type', 'property_type'])->orderBy('created_at', 'DESC');

        $estates = $estates->map(function ($query) {
            $query['estate_name'] = $query->estate->name;
            $query['form_amount'] = $query->estate->form_amount;
            return $query;
        });

        $allContracts = [];
        if ($contracts) {
            // get the customer
            $customer = Customer::withCount(['singleContract', 'joinContract', 'contracts'])->where('id', $customer_id)->where('company_id', $this->sessionDetails('company_id'))->first();
            $dataX = [
                'all_contracts' => $customer->contracts_count,
                'joint_contracts' => $customer->single_contract_count,
                'single_contracts' => $customer->join_contract_count
            ];
            $data['contract_count'] = $dataX;

            foreach ($estates as $estate) {
                // foreach ($contracts as $key => $contract) {
                $details = CustomerContract::with('location:name,id', 'building_type:id,name', 'plot_size:id,size')->where('estate_id', $estate->estate_id)->where('customer_id', $customer_id)->select(['id', 'customer_id', 'city_id', 'location_id', 'estate_id', 'property_type', 'status', 'plot_size', 'building_type', 'contract_type', 'property_type'])->orderBy('created_at', 'DESC')->where('company_id', $this->sessionDetails('company_id'))->get();

                foreach ($details as $detail) {
                    $allContracts[$estate->estate_id]['estate_id'] = $estate->estate_id;
                    $detail['form_amount'] = $estate->form_amount;
                    $allContracts[$estate->estate_id]['estate_name'] = $estate->estate_name;
                    $allContracts[$estate->estate_id]['contracts'][] = $detail;
                }
            }

            $data['all_contracts'] = $allContracts;

            return response()->json(['message' => 'success', 'data' => $data], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => null], 200);
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
