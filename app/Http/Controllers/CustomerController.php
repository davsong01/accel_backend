<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Models\CustomerContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
        $customers = Customer::with(['user','contracts:id,status,customer_id'])->withCount('contracts')
            ->where('company_id', $this->sessionDetails('company_id'))->orderBy('created_at', 'DESC');
       
        if (!empty($request->name)) {
            $customers = $customers->whereHas('user', function ($query) use ($request) {
                $query->where('first_name', 'LIKE', "%{$request->name}%")
                    ->orWhere(function ($query) use ($request) {
                        $query->where('last_name', 'LIKE', "%{$request->name}%")
                            ->orWhere('middle_name', 'LIKE', "%{$request->name}%");
                    });
            })->with(['user', 'contracts:id,status,customer_id'])
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

        return response()->json(['message' => 'Success', 'data' => $customers], 200);
    }

    public function getLeads(Request $request)
    {

        if (!empty($request->type)) {
            $leads = Customer::with(['user:id,avatar,first_name,last_name,status,gender,phone_number'])->whereHas('contracts', function ($q) use ($request) {
                $q->where('status', 'lead')->where('contract_status', $request->type);
            })->withCount(['contracts' => function ($q)  use ($request) {
                $q->where('status', 'lead')->where('contract_status', $request->type);
            }])->orderBy('customers.created_at', 'DESC');
        } else {
            $leads = Customer::with(['user:id,avatar,first_name,last_name,status,gender,phone_number'])->whereHas('contracts', function ($q) {
                $q->where('status', 'lead');
            })->withCount(['contracts' => function ($q) {
                $q->where('status', 'lead');
            }])->orderBy('customers.created_at', 'DESC');
        }

        // $leads = Customer::with(['user:id,avatar,first_name,last_name,status,gender,phone_number'])->whereHas('contracts', function ($query) {
        //     $query->where('status', 'lead');
        // })->withCount('contracts')->where('company_id', $this->sessionDetails('company_id'))
        //     ->orderBy('customers.created_at', 'DESC');

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

        return response()->json(['message' => 'Success', 'data' => $leads], 200);
    }

    public function getSubscribers(Request $request)
    {
        
        if (!empty($request->type)) {
            $subscribers = Customer::with(['user:id,avatar,first_name,last_name,status,gender,phone_number'])->whereHas('contracts', function ($q) use ($request) {
                $q->where('status', 'subscriber')->where('contract_status', $request->type);
            })->withCount(['contracts' => function ($q)  use ($request) {
                $q->where('status', 'subscriber')->where('contract_status',$request->type);
            }])->orderBy('customers.created_at', 'DESC');

        }else{
            $subscribers = Customer::with(['user:id,avatar,first_name,last_name,status,gender,phone_number'])->whereHas('contracts', function ($q) {
                $q->where('status', 'subscriber');
            })->withCount(['contracts' => function ($q) {
                $q->where('status', 'subscriber');
            }])->orderBy('customers.created_at', 'DESC');
        }

        // $subscribers = Customer::orderBy('customers.created_at')
        //     ->join('customer_contracts', 'customer_contracts.customer_id','customers.id')
        //     ->join('users', 'users.id','customers.id')
        //     ->where('customer_contracts.status','subscriber')
        //     ->select('users.avatar', 'users.title', 'users.first_name', 'users.last_name','customers.id AS id', 'users.id as user_id', 'users.customerId','customers.id','customers.created_at', DB::raw('COUNT(customer_contracts.id) AS contract_count'));

        if (!empty($request->name)) {
            $subscribers = $subscribers->whereHas('user', function ($query) use ($request) {
                $query->where('first_name', 'LIKE', "%{$request->name}%")
                    ->orWhere(function ($query) use ($request) {
                        $query->where('last_name', 'LIKE', "%{$request->name}%")
                            ->orWhere('middle_name', 'LIKE', "%{$request->name}%");
                    });
            });
        }


        $subscribers = $subscribers->paginate(20)->withQueryString();

        return response()->json(['message' => 'Success', 'data' => $subscribers], 200);
    }

    public function getProspects(Request $request)
    {
        if (!empty($request->type)) {
            $prospect = Customer::with(['user:id,avatar,first_name,last_name,status,gender,phone_number'])->whereHas('contracts', function ($q) use ($request) {
                $q->where('status', 'prospect')->where('contract_status', $request->type);
            })->withCount(['contracts' => function ($q)  use ($request) {
                $q->where('status', 'prospect')->where('contract_status', $request->type);
            }])->orderBy('customers.created_at', 'DESC');
        } else {
            $prospect = Customer::with(['user:id,avatar,first_name,last_name,status,gender,phone_number'])->whereHas('contracts', function ($q) {
                $q->where('status', 'prospect');
            })->withCount(['contracts' => function ($q) {
                $q->where('status', 'prospect');
            }])->orderBy('customers.created_at', 'DESC');
        }

        // $prospect = Customer::with(['user:id,avatar,first_name,last_name,status,gender,phone_number'])->whereHas('contracts', function ($query) {
        //     $query->where('status', 'prospect');
        // })->withCount('contracts')->where('company_id', $this->sessionDetails('company_id'))
        //     ->orderBy('customers.created_at', 'DESC');

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

        return response()->json(['message' => 'Success', 'data' => $prospect], 200);
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

        return response()->json(['message' => 'Success', 'data' => $contract], 200);
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
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (\Request::route()->getPrefix() == 'api/customer') {
            $id = Auth::user()->customer->id;
        }
       
        $customer = Customer::with(['user'])->where('id', $id)
            ->first()->load('user', 'user.state', 'user.country','user.lga');
           
        if(!$customer){
            return response()->json(['message' => 'Customer does not exist', 'data' => []], 200);
        }

        $customer->customer_id = $customer->id;
      
        // $chartOfAccount->load('currency', 'account_category:id,name', 'account_type:id,type')]
        return response()->json(['message' => 'Success', 'data' => $customer], 200);
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

        return response()->json(['message' => 'Success', 'data' => $data], 200);
    }

    public function customerIdentificationSetting()
    {
        $customer_identification_settings = CustomerIdentificationSetting::where(['company_id' => $this->sessionDetails('company_id')])->first();

        if ($customer_identification_settings) {
            $order = json_decode($customer_identification_settings->order_of_appearance, true);
            $customer_identification_settings->order_of_appearance = $order;
        } else {
            $customer_identification_settings = [];
        }

        return response()->json(['message' => 'Operation successfull', 'data' => $customer_identification_settings], 200);
    }

    public function updateCustomerIdentificationSetting(Request $request)
    {
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
                'order_of_appearance' => 'required', //|in:prefix,registration_year,registration_month,auto_increment_number,random_number',
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
        $data['order_of_appearance'] = isset($data['order_of_appearance']) ? json_encode($data['order_of_appearance']) : null;

        $data['company_id'] = $this->sessionDetails('company_id');

        CustomerIdentificationSetting::updateOrCreate(['company_id' => $data['company_id']], $data);

        return response()->json(['message' => 'Successful', 'data' => $request['order_of_appearance']], 200);
    }

   
}
