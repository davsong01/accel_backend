<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Models\CustomerContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\CustomerIdentificationSetting;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $customers = Customer::with(['user:id,avatar,first_name,last_name,status,gender,phone_number', 'contracts:id,status,customer_id'])->withCount('contracts')
            ->where('company_id', $this->sessionDetails('company_id'))->orderBy('created_at', 'DESC');

        if (!empty($request->name)) {
            $customers = $customers->whereHas('user', function ($query) use ($request) {
                $query->where('first_name', 'LIKE', "%{$request->name}%")
                    ->orWhere(function ($query) use ($request) {
                        $query->where('last_name', 'LIKE', "%{$request->name}%")
                            ->orWhere('middle_name', 'LIKE', "%{$request->name}%");
                    });
            })->with(['user:id,avatar,first_name,last_name,status,gender,phone_number', 'contracts:id,status,customer_id'])
                ->withCount('contracts')
                ->where('company_id', $this->sessionDetails('company_id'))
                ->orderBy('customers.created_at', 'DESC');
        }

        if (!empty($request->status)) {
            $request->status = explode(',', $request->status);

            $customers = $customers->whereHas('contracts', function ($query) use ($request) {
                $query->whereIn('status', $request->status);
            });
        }

        if (!empty($request->estate)) {
            $request->estate = explode(',', $request->estate);
            $customers = $customers->whereHas('contracts', function ($query) use ($request) {
                $query->whereIn('estate_id', $request->estate);
            });
        }

        if (!empty($request->property_type)) {
            $type = explode(',', $request->property_type);
            $propertyType = [];

            if (in_array('land', $type)) {
                $propertyType[] = 'land';
            }

            if (in_array('building', $type)) {
                $propertyType[] = 'building';
            }

            if (in_array('both', $type)) {
                $propertyType = ['building', 'land'];
            }

            $customers = $customers->whereHas('contracts', function ($query) use ($propertyType) {
                $query->whereIn('property_type', $propertyType);
            });
        }

        if (!empty($request->property_type_id)) {
            $request->property_type_id = explode(',', $request->property_type_id);

            $customers = $customers->whereHas('contracts', function ($query) use ($request) {
                $query->whereIn('property_type_id', $request->property_type_id);
            });
        }

        $customers = $customers->paginate(20)->withQueryString();

        foreach ($customers as $customer) {
            $statuses = $customer->contracts->pluck('status')->toArray();
            $customer->contract_statuses = array_count_values($statuses);
        }

        return response()->json(['message' => 'success', 'data' => $customers], 200);
    }

    public function getLeads(Request $request)
    {
        $leads = Customer::with(['user:id,avatar,first_name,last_name,status,gender,phone_number'])->whereHas('contracts', function ($query) {
            $query->where('status', 'lead');
        })->withCount('contracts')->where('company_id', $this->sessionDetails('company_id'))
            ->orderBy('customers.created_at', 'DESC');

        if (!empty($request->name)) {
            $leads = $leads->whereHas('user', function ($query) use ($request) {
                $query->where('first_name', 'LIKE', "%{$request->name}%")
                    ->orWhere(function ($query) use ($request) {
                        $query->where('last_name', 'LIKE', "%{$request->name}%")
                            ->orWhere('middle_name', 'LIKE', "%{$request->name}%");
                    });
            });
        }

        $leads = $leads->paginate(20)->withQueryString();

        return response()->json(['message' => 'success', 'data' => $leads], 200);
    }

    public function getSubscribers(Request $request)
    {
        $subscriber = Customer::with(['user:id,avatar,first_name,last_name,status,gender,phone_number'])->whereHas('contracts', function ($query) {
            $query->where('status', 'subscriber');
        })->withCount('contracts')->where('company_id', $this->sessionDetails('company_id'))
            ->orderBy('customers.created_at', 'DESC');

        if (!empty($request->name)) {
            $subscriber = $subscriber->whereHas('user', function ($query) use ($request) {
                $query->where('first_name', 'LIKE', "%{$request->name}%")
                    ->orWhere(function ($query) use ($request) {
                        $query->where('last_name', 'LIKE', "%{$request->name}%")
                            ->orWhere('middle_name', 'LIKE', "%{$request->name}%");
                    });
            });
        }

        $subscriber = $subscriber->paginate(20)->withQueryString();

        return response()->json(['message' => 'success', 'data' => $subscriber], 200);
    }

    public function getProspects(Request $request)
    {
        $prospect = Customer::with(['user:id,avatar,first_name,last_name,status,gender,phone_number'])->whereHas('contracts', function ($query) {
            $query->where('status', 'prospect');
        })->withCount('contracts')->where('company_id', $this->sessionDetails('company_id'))
            ->orderBy('customers.created_at', 'DESC');

        if (!empty($request->name)) {
            $prospect = $prospect->whereHas('user', function ($query) use ($request) {
                $query->where('first_name', 'LIKE', "%{$request->name}%")
                    ->orWhere(function ($query) use ($request) {
                        $query->where('last_name', 'LIKE', "%{$request->name}%")
                            ->orWhere('middle_name', 'LIKE', "%{$request->name}%");
                    });
            });
        }

        $prospect = $prospect->paginate(20)->withQueryString();

        return response()->json(['message' => 'success', 'data' => $prospect], 200);
    }

    public function CustomerCount()
    {
        // $all = CustomerContract::where('company_id', $this->sessionDetails('company_id'))->count();
        $all = Customer::count();
        $leads = Customer::whereHas('contracts', function ($query) {
            $query->where('status', 'lead');
        })->where('company_id', $this->sessionDetails('company_id'))->count();

        $subscriber = Customer::whereHas('contracts', function ($query) {
            $query->where('status', 'subscriber');
        })->where('company_id', $this->sessionDetails('company_id'))->count();

        $prospect = Customer::whereHas('contracts', function ($query) {
            $query->where('status', 'prospect');
        })->where('company_id', $this->sessionDetails('company_id'))->count();

        $contract = [
            'all' => $all,
            'lead' => $leads,
            'subscriber' => $subscriber,
            'prospect' => $prospect,
        ];

        return response()->json(['message' => 'success', 'data' => $contract], 200);
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
        // $validator = Validator::make($request->all(), [
        //     'first_name' => 'required',
        //     'last_name' => 'required',
        //     'gender' => 'required|in:male,female',
        //     'email' => [
        //         'required', 'email', Rule::unique('users')->where(function ($query) {
        //             $query->where('company_id', $this->sessionDetails('company_id'))->where('type', 'customer');
        //         })
        //     ],
        //     'phone_number' => 'required',
        //     // 'city' => 'required|exists:cities,id,company_id,' . $this->sessionDetails('company_id'),
        //     // 'location' => 'required|exists:locations,id,company_id,' . $this->sessionDetails('company_id'),
        //     // 'estate' => 'required|exists:estates,id,company_id,' . $this->sessionDetails('company_id'),
        //     'residential_address' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'error' => $validator->messages()->first(),
        //         'data' => null,
        //     ], 412);
        // }

        // $data['additional_details'] = json_encode([
        //     // 'alternate_number' => $data['alternate_number'] ?? '',
        //     // 'alternate_email' => $data['alternate_email'] ?? '',
        //     // 'state' => $data['state'] ?? '',
        //     'residential_address' => $data['residential_address'] ?? '',
        //     // 'marital_status' => $data['marital_status'] ?? '',
        //     // 'nationality' => $data['nationality'] ?? '',
        //     // 'lga' => $data['lga'] ?? '',
        // ]);

        // // Add User
        // $user = User::updateOrCreate([
        //     'first_name' => $request->first_name,
        //     'email' => $request->email,
        //     'gender' => $request->gender,
        //     'last_name' => $request->last_name,
        //     'phone_number' => $request->phone_number,
        //     'additional_details' => $data['additional_details'],
        //     'company_id' => $this->sessionDetails('company_id'),
        //     'branch_id' => $this->sessionDetails('branch_id'),
        //     'email_verified_at' => now(),
        //     'status' => 'active',
        //     'type' => 'customer',
        //     'password' => Hash::make('123456'),
        //     'company_id' => $this->sessionDetails('company_id'),
        //     'branch_id' => $this->sessionDetails('branch_id'),
        // ]);

        // if ($user) {
        //     // Add Customer
        //     $customer = Customer::updateOrCreate([
        //         'company_id' => $this->sessionDetails('company_id'),
        //         'branch_id' => $this->sessionDetails('branch_id'),
        //         'user_id' => $user->id,
        //         'verified' => 1,
        //     ]);

        //     $user->update([
        //         'customerId' => $this->generateCustomerId($user, true),
        //     ]);
        // }

        // return response()->json(['message' => 'success', 'data' => $customer], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Customer::where('customers.id', $id)->join('users', 'users.id', '=', 'customers.user_id')
            ->select('users.status', 'users.type', 'users.first_name', 'users.last_name', 'users.middle_name', 'users.phone_number', 'users.residential_address', 'users.gender', 'users.email', 'users.contact_information', 'users.next_of_kin', 'users.additional_information', 'job_information', 'customers.id AS customer_id')
            ->first();
        return response()->json(['message' => 'success', 'data' => $user], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }

    public function singleCustomerContractCount($customer_id)
    {
        $customer = Customer::withCount(['singleContract', 'jointContract', 'contracts'])->where('id', $customer_id)->where('company_id', $this->sessionDetails('company_id'))->first();

        if (!$customer) {
            return response()->json(['message' => 'No record found', 'data' => null], 404);
        }

        $data = [
            'all_contracts' => $customer->contracts_count,
            'joint_contracts' => $customer->single_contract_count,
            'single_contracts' => $customer->join_contract_count
        ];

        return response()->json(['message' => 'success', 'data' => $data], 200);
    }

    public function customerIdentificationSetting(){
        $staff_ientification_settings = CustomerIdentificationSetting::where(['company_id' => $this->sessionDetails('company_id')])->get();
        return response()->json(['message' => 'successfull', 'data' => $staff_ientification_settings], 200);
    }

    public function updateCustomerIdentificationSetting(Request $request){
        $validator = Validator::make($request->all(), [
            'use_id_generator' => 'in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => [],
            ], 412);
        }

        if ($request->use_id_generator == 1) {
            $validator = Validator::make($request->all(), [
                'order_of_appearance' => 'required', // | prefix,registration_year,registration_month,auto_increment_number,random_number
                'next_auto_increment_number' => 'nullable',
                'auto_increment_number_digit' => 'nullable',
                'prefix' => 'nullable',
                'appearance_format' => 'nullable',
            ]);
        }
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $data = $validator->valid();
        $data['order_of_appearance'] = json_encode($data['order_of_appearance']);
        $data['company_id'] = $this->sessionDetails('company_id');
      
        CustomerIdentificationSetting::updateOrCreate(['company_id' => $data['company_id']], $data);

        return response()->json(['message' => 'Successful', 'data' => []], 200);
    }
}
