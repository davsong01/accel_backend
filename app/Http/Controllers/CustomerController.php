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

        return response()->json(['message' => 'Success', 'data' => $customers], 200);
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

        return response()->json(['message' => 'Success', 'data' => $leads], 200);
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

        return response()->json(['message' => 'Success', 'data' => $subscriber], 200);
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
        $customer = Customer::where('customers.id', $id)
            ->with('user')->first();
            
        $customer->customer_id = $customer->id;

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

    public function updateUser($user, $data)
    {
        $old_additional_details = !empty($user->additional_details) ? $user->additional_details : [];
        $old_means_of_identification = !empty($user->means_of_identification) ? $user->means_of_identification : [];
        $old_next_of_kin = !empty($user->next_of_kin) ? $user->next_of_kin : [];

        $additional_details = json_encode([
            'title' => !empty($data['title']) ? $data['title'] : ($old_additional_details['title'] ?? ''),
            'alternate_number' => !empty($data['alternate_number']) ? $data['alternate_number'] : ($old_additional_details['alternate_number'] ?? ''),
            'alternate_email' => !empty($data['alternate_email']) ? $data['alternate_email'] : ($old_additional_details['alternate_email'] ?? ''),
            'state' => !empty($data['state_id']) ? $data['state_id'] : ($old_additional_details['state'] ?? ''),
            'residential_address' => isset($data['contact_details']['house_address']) ? $data['contact_details']['house_address'] : ($old_additional_details['residential_address'] ?? ''),
            'marital_status' => !empty($data['marital_status']) ? $data['marital_status'] : ($old_additional_details['marital_status'] ?? ''),
            'nationality' => !empty($data['country_id']) ? $data['country_id'] : ($old_additional_details['nationality'] ?? ''),
            'lga' => !empty($data['lga_id']) ? $data['lga_id'] : ($old_additional_details['lga'] ?? ''),
            'office_address' => isset($data['contact_details']['office_address']) ? $data['contact_details']['office_address'] : ($old_additional_details['office_address'] ?? ''),
        ]);

        $means_of_identification = json_encode([
            'occupation' => isset($data['occupation']) ? $data['occupation'] : ($old_means_of_identification['occupation'] ?? ''),
            'place_of_work' => isset($data['place_of_work']) ? $data['place_of_work'] : ($old_means_of_identification['place_of_work'] ?? ''),
            'id_card_type' => isset($data['id_card_type']) ? $data['id_card_type'] : ($old_means_of_identification['id_card_type'] ?? ''),
            'passport_photo' => isset($data['passport_photo']) ? $data['passport_photo'] : ($old_means_of_identification['passport_photo'] ?? ''),
            'id_card' => isset($data['id_card']) ? $data['id_card'] : ($old_means_of_identification['id_card'] ?? ''),
            'signature' => isset($data['signature']) ? $data['signature'] : ($old_means_of_identification['signature'] ?? ''),
        ]);

        $next_of_kin = json_encode([
            'nok_first_name' => isset($data['next_of_kin_information']['nok_first_name']) ? $data['next_of_kin_information']['nok_first_name'] : ($old_next_of_kin['nok_first_name'] ?? ''),
            'nok_last_name' => isset($data['next_of_kin_information']['nok_last_name']) ? $data['next_of_kin_information']['nok_last_name'] : ($old_next_of_kin['nok_last_name'] ?? ''),
            'nok_phone_number' => isset($data['next_of_kin_information']['nok_phone_number']) ? $data['next_of_kin_information']['nok_phone_number'] : ($old_next_of_kin['nok_phone_number'] ?? ''),
            'nok_house_address' => isset($data['next_of_kin_information']['nok_house_address']) ? $data['next_of_kin_information']['nok_house_address'] : ($old_next_of_kin['nok_house_address'] ?? ''),
            'nok_email_address' => isset($data['next_of_kin_information']['nok_email_address']) ? $data['next_of_kin_information']['nok_email_address'] : ($old_next_of_kin['nok_email_address'] ?? ''),
            'nok_relationship' => isset($data['next_of_kin_information']['nok_relationship']) ? $data['next_of_kin_information']['nok_relationship'] : ($old_next_of_kin['nok_relationship'] ?? ''),
        ]);

        $user->update([
            'first_name' => $data['first_name'] ?? $user->first_name,
            'middle_name' => $data['middle_name'] ?? $user->middle_name,
            'branch_id' => $data['branch_id'] ?? $user->branch_id,
            'email' => $data['email'] ?? $user->email,
            'date_of_birth' => $data['date_of_birth'] ?? $user->date_of_birth,
            'gender' => $data['gender'] ?? $user->gender,
            'last_name' => $data['last_name'] ?? $user->last_name,
            'phone_number' => $data['phone_number'] ?? $user->phone_number,
            'additional_details' => $additional_details ?? null,
            'next_of_kin' => $next_of_kin ?? null,
            'means_of_identification' => $means_of_identification ?? null,
        ]);

        return $user;
    }
}
