<?php

namespace App\Http\Controllers;

use App\Models\BuildingType;
use Carbon\Carbon;
use App\Models\City;
use App\Models\User;
use App\Models\Staff;
use App\Models\Estate;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Models\CustomerContract;
use App\Models\CustomerForm;
use App\Models\LegalDocumentType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Models\GeneratedLegalDocument;
use App\Models\PlotSize;
use App\Services\LogService;
use Illuminate\Support\Facades\Validator;

class CustomerContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $customer_id)
    {
        $contracts = CustomerContract::with('contract_payment', 'property_type_details', 'city:name,id', 'estate:name,id', 'location:name,id', 'building_type:id,name', 'plot_size:id,size')->where('customer_id', $customer_id)->where('company_id', $this->sessionDetails('company_id'))->select(['id', 'primary_marketer_id', 'property_type_id', 'customer_id', 'city_id', 'location_id', 'estate_id', 'property_type', 'status', 'contract_status', 'plot_size', 'building_type', 'subscribers', 'contract_type', 'property_type', 'transaction_date', 'created_at', 'form_payment_transaction_id', 'contract_payment_transaction_id', 'discount']);

        $allContracts = [];
        if ($contracts->exists()) {
            if (!empty($request->contract_type)) {
                $contracts = $contracts->where('contract_type', $request->contract_type);
            }
            // ->groupBy('estate_id')
            $contracts = $contracts->orderBy('created_at', 'DESC')
                ->get();
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

            $data['customer_details'] = [
                'id' => $customerY->id,
                'first_name' => $customerY->user->first_name,
                'last_name' => $customerY->user->last_name,
                'middle_name' => $customerY->user->middle_name,
                'customerID' => $customerY->user->customerId
            ];
            foreach ($contracts as $key => $contract) {
                // $estate->load('city', 'location', 'estate_property_type')
                $contract->primary_marketer = $contract->primaryMarketer($contract);
                $contract->subscribers = !empty($contract->subscribers) ? json_decode($contract->subscribers) : '';
                $contract['location'] = $contract->location->name;
                $allContracts[$key] = $contract;

                $contract->generated_legal_documents = $this->generatedDocuments($contract);
                $contract->legal_document_type_ids = $this->prepareDocumentTypeIds($contract);
                // $allContracts[$key]['form_amount'] = $this->getContractFormAmount($contract, 'single');
            }

            $data['all_contracts'] = $allContracts;

            return response()->json(['message' => 'Success', 'data' => $data], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => []], 200);
        }
    }

    public function generatedDocuments($contract)
    {
        $generated = GeneratedLegalDocument::with('template')->where('contract_id', $contract->id)->get();
        $alldocumts = [];

        foreach ($generated as $generate) {
            $alldocumts[$generate->template->legalDocumentType->name][] = [
                'legal_document_template_id' => $generate->template->id,
                'legal_document_type_id' => $generate->template->legalDocumentType->id,
                'legal_document_name' => $generate->template->legalDocumentType->name,
                'legal_document_type' => $generate->template->legalDocumentType->type,
                'regenerate_count' => $generate->regenerate_count,
            ];
        }

        return $alldocumts;
    }

    public function prepareDocumentTypeIds($contract)
    {
        $documents = LegalDocumentType::where('type', strtoupper($contract->property_type))->get(['id', 'name', 'type']);
        $alldocs = [];
        foreach ($documents as $generate) {
            $alldocs[$generate->name] = $generate->id;
        }

        return $alldocs;
    }

    public function getSingleCustomerContract($customer_id)
    {
        if (auth()->user()->customer->id != $customer_id) {
            return response()->json([
                'error' => 'Invalid customer id',
                'data' => [],
            ], 422);
        }

        $request = new Request();

        return $this->index($request, $customer_id);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function customerPaymentHistory(Request $request, $customer_id)
    {
        $histories = Transaction::with([
            'form_payment_contracts:id,property_type_id,form_payment_transaction_id,contract_payment_transaction_id,sei_payment_transaction_id,property_type,estate_id,plot_size,building_type',
            'property_payment_contracts:id,property_type_id,form_payment_transaction_id,sei_payment_transaction_id,contract_payment_transaction_id,property_type,estate_id,plot_size,building_type','estate:id,name', 'sei_payment_transactions:id,property_type_id,form_payment_transaction_id,contract_payment_transaction_id,sei_payment_transaction_id,property_type,estate_id,plot_size,building_type','transaction_entry'
        ])->where('customer_id', $customer_id)->orderBy('created_at', 'DESC');
        $contract_id = $request->contract_id;
        
        if (!empty($request->contract_id)) {
            // Get contract 
            $contract = CustomerContract::where('id', $contract_id)->first();

            $histories = $histories->where(function ($query) use ($contract) {
                $query->where('transaction_id', $contract->contract_payment_transaction_id)
                    ->orWhere('transaction_id', $contract->form_payment_transaction_id)
                    ->get();
            });
        }
        // ->where(['customer_id' => $customer_id, 'company_id' => $this->sessionDetails('company_id'), 'purpose' => 'form_payment'])->whereHas('contracts')->orderBy('created_at', 'DESC')->get();

        $histories = $histories->paginate(20)->withQueryString();
        // dd($histories);
        return response()->json(['message' => 'Success', 'data' => $histories], 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!empty($request->customer_id)) {
            // Existing customer (and contract)
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
                'primary_marketer_id' => 'nullable|exists:staff,id,company_id,' . $this->sessionDetails('company_id'),
                'secondary_marketers' => 'nullable',
                'subscribers' => 'nullable',
            ]);


            if (isset($request->secondary_marketers) && in_array($request->primary_marketer_id, $request->secondary_marketers)) {
                return response()->json([
                    'error' => 'Primary Marketer cannot be a secondary marketer at the same time, please check input',
                    'data' => null,
                ], 412);
            }

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
                $data['primary_marketer_id'] = $data['primary_marketer_id'] ?? null;
                $data['secondary_marketers'] = isset($data['secondary_marketers']) && !empty($data['secondary_marketers']) ? json_encode($data['secondary_marketers']) : json_encode([]);

                $this->createCustomerContract($data);
            }
            // Send contract email to this existing customer
            $customer = Customer::where('id', $request->customer_id)->first();
            $city = City::where('id', $request->city_id)->first();
            $estate = Estate::where('id', $request->estate_id)->first();
            $location = Location::where('id', $request->location_id)->first();
            $plotsize = PlotSize::where('id', $request->plot_size)->first();
            $building_type = BuildingType::where('id', $request->building_type)->first();

            $data['plot_size'] = $plotsize->size ?? null;
            $data['building_type'] = $building_type->name ?? null;
            $data['type'] = 'add.contract.to.existing.customer';
            $data['name'] =  $customer->user->first_name;
            $data['email'] =  $customer->user->email;
            $data['property_type']  = ucfirst($request->property_type);
            $data['city'] = $city->name;
            $data['location'] = $location->name;
            $data['estate'] = $estate->name;
            $data['no_of_units'] = $request->no_of_units;
            $data['contract_type'] = ucfirst($data['contract_type']);

            $this->logEmail($data);

            // Log Primary marketer email
            if ($data['primary_marketer_id']) {
                $data['type'] = 'primary.marketer.email.for.new.customer';
                $markerter = Staff::where('id', $data['primary_marketer_id'])->first();

                if ($markerter->user) {
                    $data['name'] =  $markerter->user->first_name;
                    $data['customer_details'] = [
                        'customer_name' => $customer->user->first_name . ' ' . $customer->user->last_name,
                        'customer_phone' => $customer->user->phone . ' ' . $customer->user->phone,
                        'customerId' => $customer->user->customerId,
                    ];
                    $data['customer_name'] =  $customer->user->first_name;
                    $data['email'] =  $markerter->user->email;

                    $this->logEmail($data);
                }
            }

            // Log subscriber email
            $email['property_type'] = ucfirst($request->property_type);
            $email['city'] = $city->name;
            $email['location'] = $location->name;
            $email['estate'] = $estate->name;
            $email['no_of_units'] = $request->no_of_units;
            $email['contract_type'] = ucfirst($data['contract_type']);
            $email['plot_size'] = $plotsize->size ?? null;
            $email['building_type'] = $building_type->name ?? null;

            $subscribers = json_decode($data['subscribers']);
            if (!empty($subscribers) && count($subscribers) > 0) {
                foreach ($subscribers as $subscribe) {
                    $email['type'] = 'send.email.to.secondary.subscriber';
                    $email['name'] =  $subscribe->s_name;
                    $email['email'] =  $subscribe->s_email;
                    $this->logEmail($email);
                }
            }

            LogService::admin(auth()->user()->id, $customer->user->id, 'Create a customer contract', 'CREATE');
            return response()->json(['message' => 'Success', 'data' => ['customer_id' => $data['customer_id']]], 200);
        } else {
            // New customer and contract
            $validator = Validator::make($request->all(), [
                'city_id' => 'required|exists:cities,id,company_id,' . $this->sessionDetails('company_id'),
                'location_id' => 'required|exists:locations,id,company_id,' . $this->sessionDetails('company_id'),
                'country_id' => 'nullable|exists:countries,id',
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
                        $query->where('company_id', $this->sessionDetails('company_id'))->where('type', 'customer');
                    })
                ],
                'phone_number' => 'required',
                'residential_address' => 'required',
                'primary_marketer_id' => 'nullable|exists:staff,id,company_id,' . $this->sessionDetails('company_id'),
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

            $customer = Customer::where('id', $request->customer_id)->first();
            $city = City::where('id', $request->city_id)->first();
            $estate = Estate::where('id', $request->estate_id)->first();
            $location = Location::where('id', $request->location_id)->first();
            $plotsize = PlotSize::where('id', $request->plot_size)->first();
            $building_type = BuildingType::where('id', $request->building_type)->first();

            $email['plot_size'] = $plotsize->size ?? null;
            $email['building_type'] = $building_type->name ?? null;

            $email['property_type'] = ucfirst($request->property_type);
            $email['city'] = $city->name;
            $email['location'] = $location->name;
            $email['estate'] = $estate->name;
            $email['no_of_units'] = $request->no_of_units;
            $email['contract_type'] = ucfirst($data['contract_type']);

            // Add user
            $user = $this->createUser($data);
            $data['user_id'] = $user->id;

            // Add customer
            if ($user) {
                $customer = $this->createCustomer($data);

                // Log email to customer
                $email['type'] = 'user.store';
                $email['name'] =  $user->first_name;
                $email['email'] =  $user->email;
                $email['customer_id'] =  $user->customerId;
                $this->logEmail($email);


                // Log subscriber email
                $subscribers = json_decode($data['subscribers']);
                if (count($subscribers) > 0) {
                    foreach ($subscribers as $subscribe) {
                        $email['type'] = 'send.email.to.secondary.subscriber';
                        $email['name'] =  $subscribe->s_name;
                        $email['email'] =  $subscribe->s_email;
                        $this->logEmail($email);
                    }
                }
            } else {
                return response()->json(['message' => 'Could not create user', 'data' => null], 422);
            }

            if ($customer) {
                // Add contract
                foreach ($range as $units) {
                    $data['customer_id'] = $customer->id;
                    $data['secondary_marketers'] = isset($data['secondary_marketers']) && !empty($data['secondary_marketers']) ? json_encode($data['secondary_marketers']) : json_encode([]);

                    $this->createCustomerContract($data);
                }

                if ($data['primary_marketer_id']) {
                    $email['type'] = 'primary.marketer.email.for.new.contract';
                    $markerter = Staff::where('id', $data['primary_marketer_id'])->first();

                    if ($markerter) {
                        $email['name'] =  $markerter->user->first_name;

                        $email['customer_details'] = [
                            'customer_name' => $customer->user->first_name . ' ' . $customer->user->last_name,
                            'customer_phone' => $customer->user->phone . ' ' . $customer->user->phone,
                            'customerId' => $customer->user->customerId,
                        ];
                        $email['customer_name'] =  $customer->user->first_name;
                        $email['email'] =  $markerter->user->email;

                        $this->logEmail($email);
                    }
                }
            } else {
                return response()->json(['message' => 'Could not create customer', 'data' => null], 422);
            }

            LogService::admin(auth()->user()->id, $customer->user->id, 'Create a customer contract', 'CREATE');
            return response()->json(['message' => 'Success', 'data' => ['customer_id' => $customer->id]], 200);
        }

        return response()->json(['message' => 'Success', 'data' => null], 200);
    }

    public function contractPayment(Request $request)
    {
        if (!empty($request->customer_id)) {
            // Existing customer (and contract)
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
                'primary_marketer_id' => 'nullable|exists:staff,id,company_id,' . $this->sessionDetails('company_id'),
                'secondary_marketers' => 'nullable',
                'subscribers' => 'nullable',
            ]);


            if (isset($request->secondary_marketers) && in_array($request->primary_marketer_id, $request->secondary_marketers)) {
                return response()->json([
                    'error' => 'Primary Marketer cannot be a secondary marketer at the same time, please check input',
                    'data' => null,
                ], 412);
            }

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
                $data['primary_marketer_id'] = $data['primary_marketer_id'] ?? null;
                $data['secondary_marketers'] = isset($data['secondary_marketers']) && !empty($data['secondary_marketers']) ? json_encode($data['secondary_marketers']) : json_encode([]);

                $this->createCustomerContract($data);
            }
            // Send contract email to this existing customer
            $customer = Customer::where('id', $request->customer_id)->first();
            $city = City::where('id', $request->city_id)->first();
            $estate = Estate::where('id', $request->estate_id)->first();
            $location = Location::where('id', $request->location_id)->first();
            $plotsize = PlotSize::where('id', $request->plot_size)->first();
            $building_type = BuildingType::where('id', $request->building_type)->first();

            $data['plot_size'] = $plotsize->size ?? null;
            $data['building_type'] = $building_type->name ?? null;
            $data['type'] = 'add.contract.to.existing.customer';
            $data['name'] =  $customer->user->first_name;
            $data['email'] =  $customer->user->email;
            $data['property_type']  = ucfirst($request->property_type);
            $data['city'] = $city->name;
            $data['location'] = $location->name;
            $data['estate'] = $estate->name;
            $data['no_of_units'] = $request->no_of_units;
            $data['contract_type'] = ucfirst($data['contract_type']);

            $this->logEmail($data);

            // Log Primary marketer email
            if ($data['primary_marketer_id']) {
                $data['type'] = 'primary.marketer.email.for.new.customer';
                $markerter = Staff::where('id', $data['primary_marketer_id'])->first();

                if ($markerter->user) {
                    $data['name'] =  $markerter->user->first_name;
                    $data['customer_details'] = [
                        'customer_name' => $customer->user->first_name . ' ' . $customer->user->last_name,
                        'customer_phone' => $customer->user->phone . ' ' . $customer->user->phone,
                        'customerId' => $customer->user->customerId,
                    ];
                    $data['customer_name'] =  $customer->user->first_name;
                    $data['email'] =  $markerter->user->email;

                    $this->logEmail($data);
                }
            }

            // Log subscriber email
            $email['property_type'] = ucfirst($request->property_type);
            $email['city'] = $city->name;
            $email['location'] = $location->name;
            $email['estate'] = $estate->name;
            $email['no_of_units'] = $request->no_of_units;
            $email['contract_type'] = ucfirst($data['contract_type']);

            $subscribers = json_decode($data['subscribers']);
            if (!empty($subscribers) && count($subscribers) > 0) {
                foreach ($subscribers as $subscribe) {
                    $email['type'] = 'send.email.to.secondary.subscriber';
                    $email['name'] =  $subscribe->s_name;
                    $email['email'] =  $subscribe->s_email;
                    $this->logEmail($email);
                }
            }

            LogService::admin(auth()->user()->id, $customer->user->id, 'Create a customer contract', 'CREATE');
            return response()->json(['message' => 'Success', 'data' => ['customer_id' => $data['customer_id']]], 200);
        } else {
            // New customer and contract
            $validator = Validator::make($request->all(), [
                'city_id' => 'required|exists:cities,id,company_id,' . $this->sessionDetails('company_id'),
                'location_id' => 'required|exists:locations,id,company_id,' . $this->sessionDetails('company_id'),
                'country_id' => 'nullable|exists:countries,id',
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
                        $query->where('company_id', $this->sessionDetails('company_id'))->where('type', 'customer');
                    })
                ],
                'phone_number' => 'required',
                'residential_address' => 'required',
                'primary_marketer_id' => 'nullable|exists:staff,id,company_id,' . $this->sessionDetails('company_id'),
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

            $customer = Customer::where('id', $request->customer_id)->first();
            $city = City::where('id', $request->city_id)->first();
            $estate = Estate::where('id', $request->estate_id)->first();
            $location = Location::where('id', $request->location_id)->first();
            $plotsize = PlotSize::where('id', $request->plot_size)->first();
            $building_type = BuildingType::where('id', $request->building_type)->first();

            $email['property_type'] = ucfirst($request->property_type);
            $email['city'] = $city->name;
            $email['location'] = $location->name;
            $email['plot_size'] = $plotsize->size ?? null;
            $email['building_type'] = $building_type->name ?? null;
            $email['estate'] = $estate->name;
            $email['no_of_units'] = $request->no_of_units;
            $email['contract_type'] = ucfirst($data['contract_type']);

            // Add user
            $user = $this->createUser($data);
            $data['user_id'] = $user->id;

            // Add customer
            if ($user) {
                $customer = $this->createCustomer($data);

                // Log email to customer
                $email['type'] = 'user.store';
                $email['name'] =  $user->first_name;
                $email['email'] =  $user->email;
                $email['customer_id'] =  $user->customerId;
                $this->logEmail($email);


                // Log subscriber email
                $subscribers = json_decode($data['subscribers']);
                if (count($subscribers) > 0) {
                    foreach ($subscribers as $subscribe) {
                        $email['type'] = 'send.email.to.secondary.subscriber';
                        $email['name'] =  $subscribe->s_name;
                        $email['email'] =  $subscribe->s_email;
                        $this->logEmail($email);
                    }
                }
            } else {
                return response()->json(['message' => 'Could not create user', 'data' => null], 422);
            }

            if ($customer) {
                // Add contract
                foreach ($range as $units) {
                    $data['customer_id'] = $customer->id;
                    $data['secondary_marketers'] = isset($data['secondary_marketers']) && !empty($data['secondary_marketers']) ? json_encode($data['secondary_marketers']) : json_encode([]);

                    $this->createCustomerContract($data);
                }

                if ($data['primary_marketer_id']) {
                    $email['type'] = 'primary.marketer.email.for.new.contract';
                    $markerter = Staff::where('id', $data['primary_marketer_id'])->first();

                    if ($markerter) {
                        $email['name'] =  $markerter->user->first_name;
                        $email['customer_details'] = [
                            'customer_name' => $customer->user->first_name . ' ' . $customer->user->last_name,
                            'customer_phone' => $customer->user->phone . ' ' . $customer->user->phone,
                            'customerId' => $customer->user->customerId,
                        ];
                        $email['customer_name'] =  $customer->user->first_name;
                        $email['email'] =  $markerter->user->email;

                        $this->logEmail($email);
                    }
                }
            } else {
                return response()->json(['message' => 'Could not create customer', 'data' => null], 422);
            }

            LogService::admin(auth()->user()->id, $customer->user->id, 'Create a customer contract', 'CREATE');
            return response()->json(['message' => 'Success', 'data' => ['customer_id' => $customer->id]], 200);
        }

        return response()->json(['message' => 'Success', 'data' => null], 200);
    }

    public function createUser($request)
    {
        // Add User

        $user = User::updateOrCreate([
            'first_name' => $request['first_name'] ?? null,
            'middle_name' => $request['middle_name'] ?? null,
            'last_name' => $request['last_name'] ?? null,
            'phone_number' => $request['phone_number'] ?? null,
            'residential_address' => $request['residential_address'] ?? null,
            'date_of_birth' => (isset($data['date_of_birth']) && !empty($data['date_of_birth'])) ? Carbon::parse($request->date_or_birth) : null,
            'password' => Hash::make($request['password'] ?? '123456789'),
            'email' => $request['email'],
            'gender' => $request['gender'] ?? null,
            'username' => $request['username'] ?? null,
            'alternate_number' => $request['alternate_number'] ?? null,
            'alternate_email' => $request['alternate_email'] ?? null,
            'country_id' => $request['country_id'] ?? null,
            'state_id' => $request['state_id'] ?? null,
            'lga_id' => $request['lga_id'] ?? null,

            'company_id' => $this->sessionDetails('company_id'),
            'branch_id' => $this->sessionDetails('branch_id'),
            'email_verified_at' => now(),
            'status' => 'active',
            'type' => 'customer',
        ]);

        $user->update(['customerId' => $this->generateCustomerId(true, $user->created_at)]);

        LogService::admin(auth()->user()->id, $user->id, 'Create or update a user', 'CREATE');
        return $user;
    }

    public function updateUser($user, $data)
    {
        $old_next_of_kin = !empty($user->next_of_kin) ? $user->next_of_kin : [];


        $next_of_kin = json_encode([
            'nok_first_name' => isset($data['nok_first_name']) ? $data['nok_first_name'] : ($old_next_of_kin['nok_first_name'] ?? ''),
            'nok_last_name' => isset($data['nok_last_name']) ? $data['nok_last_name'] : ($old_next_of_kin['nok_last_name'] ?? ''),
            'nok_phone_number' => isset($data['nok_phone_number']) ? $data['nok_phone_number'] : ($old_next_of_kin['nok_phone_number'] ?? ''),
            'nok_house_address' => isset($data['nok_house_address']) ? $data['nok_house_address'] : ($old_next_of_kin['nok_house_address'] ?? ''),
            'nok_email_address' => isset($data['nok_email_address']) ? $data['nok_email_address'] : ($old_next_of_kin['nok_email_address'] ?? ''),
            'nok_relationship' => isset($data['nok_relationship']) ? $data['nok_relationship'] : ($old_next_of_kin['nok_relationship'] ?? ''),
        ]);

        $user->update([
            'title' => $data['title'] ?? $user->title,
            'first_name' => $data['first_name'] ?? $user->first_name,
            'middle_name' => $data['middle_name'] ?? $user->middle_name,
            'last_name' => $data['last_name'] ?? $user->last_name,
            'phone_number' => $data['phone_number'] ?? $user->phone_number,
            'residential_address' => $data['residential_address'] ?? $user->residential_address,

            'branch_id' => $data['branch_id'] ?? $user->branch_id,
            'email' => $data['email'] ?? $user->email,
            'date_of_birth' => (isset($data['date_of_birth']) && !empty($data['date_of_birth'])) ? Carbon::parse($data['date_of_birth']) : $user->date_of_birth,
            'gender' => $data['gender'] ?? $user->gender,
            'email' => $data['email'] ?? $user->email,
            'username' => $data['username'] ?? $user->username,
            'avatar' => (isset($data['passport_photo']) && !empty($data['passport_photo'])) ? $data['passport_photo'] : $user->passport_photo,
            'next_of_kin' => $next_of_kin ?? $old_next_of_kin,
            'alternate_number' => $data['alternate_number'] ?? $user->alternate_number,
            'alternate_email' => $data['alternate_email'] ?? $user->alternate_email,
            'marital_status' => $data['marital_status'] ?? $user->marital_status,
            'country_id' => $data['country_id'] ?? $user->country_id,
            'state_id' => $data['state_id'] ?? $user->state_id,
            'lga_id' => $data['lga_id'] ?? $user->lga_id,
        ]);

        LogService::admin(auth()->user()->id, $user->id, 'Update a user', 'UPDATE');
        return $user;
    }


    public function createCustomer($data)
    {
        $customer = Customer::updateOrCreate([
            'company_id' => $this->sessionDetails('company_id'),
            'branch_id' => $this->sessionDetails('branch_id'),
            'user_id' => $data['user_id'],
            'verified' => 1,
            'occupation' => $data['occupation'] ?? null,
            'place_of_work' => $data['place_of_work'] ?? null,
            'id_card_type' => $data['id_card_type'] ?? null,
            'id_card' => $data['id_card'] ?? null,
            'signature' => $data['signature'] ?? null,
            'office_address' => $data['office_address'] ?? null,
        ]);

        LogService::admin(auth()->user()->id, $customer->user->id, 'Create or update a customer', 'CREATE');
        return $customer;
    }

    public function createCustomerContract($data)
    {
        $contract = CustomerContract::updateOrCreate([
            'customer_id' => $data['customer_id'],
            'primary_marketer_id' => $data['primary_marketer_id'] ?? null,
            'secondary_marketers' => $data['secondary_marketers'] ?? null,
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

        LogService::admin(auth()->user()->id,  $contract->customer?->user?->id, 'Create or update a customer contract (' . $contract->id . ')', 'CREATE');
        return $contract;
    }

    public function updateCustomerContract($data, $contract_id, $customer_id)
    {
        $contract = CustomerContract::with('customer', 'property_type_details:type,id,estate_price,settings_and_excavation,infrastructural_fee', 'city:name,id', 'estate:name,id', 'location:name,id', 'building_type:id,name', 'plot_size:id,size', 'plotSize:id,size')->where('id', $contract_id)->where('customer_id', $customer_id)->first();
        
        if (!$contract) {
            return;
        }
        
        $contract->update([
            'primary_marketer_id' => !empty($data['primary_marketer']) ? $data['primary_marketer'] : $contract->primary_marketer_id,
            'secondary_marketers' => !empty($data['secondary_marketers']) ? json_encode($data['secondary_marketers']) : $contract->secondary_marketers,
            'city_id' => !empty($data['city_id']) ? $data['city_id'] : $contract->city_id,
            'location_id' => !empty($data['location_id']) ? $data['location_id'] : $contract->location_id,
            'estate_id' => !empty($data['estate_id']) ? $data['estate_id'] : $contract->estate_id,
            'plot_size' => !empty($data['plot_size']) ? $data['plot_size'] : $contract->plot_size,
            'building_type' => !empty($data['building_type']) ? $data['building_type'] : $contract->building_type,
            'property_type' => !empty($data['property_type']) ? $data['property_type'] : $contract->property_type,
            'property_type_id' => !empty($data['property_type_id']) ? $data['property_type_id'] : $contract->property_type_id,
            'subscribers' => !empty($data['subscribers']) ? json_encode($data['subscribers']) : $contract->subscribers,
            'contract_type' => !empty($data['contract_type']) ? $data['contract_type'] : $contract->contract_type,
            'transaction_date' => !empty($data['transaction_date']) ? $data['transaction_date'] : $contract->transaction_date,
            'discount' => !empty($data['discount']) ? $data['discount'] : $contract->discount,
        ]);

        LogService::admin(auth()->user()->id,  $contract->customer?->user?->id, 'Update a customer contract (' . $contract->id . ')', 'UPDATE');
        return;
    }

    /**
     * Display the specified resource.
     */
    public function show($contract_id)
    {
        $contract = CustomerContract::with('property_type_details:type,id,estate_price,settings_and_excavation,infrastructural_fee', 'city:name,id', 'estate:name,id', 'location:name,id', 'building_type:id,name', 'plot_size:id,size')->where('id', $contract_id)->select(['id', 'property_type_id', 'customer_id', 'city_id', 'location_id', 'estate_id', 'property_type', 'status', 'plot_size', 'building_type', 'company_id', 'subscribers', 'contract_type', 'property_type', 'primary_marketer_id'])->orderBy('created_at', 'DESC')->where('company_id', $this->sessionDetails('company_id'))->first();

        if ($contract) {
            $contract->subscribers = !empty($contract->subscribers) ? json_decode($contract->subscribers) : '';
            $contract->primary_marketer = $contract->primaryMarketer($contract);
            return response()->json(['message' => 'Success', 'data' => $contract], 200);
        }

        return response()->json(['message' => 'No record found', 'data' => null], 200);
    }

    public function makeFormPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contract_ids' => 'required',
            'account_id' => 'required|exists:chart_of_accounts,id,company_id,' . $this->sessionDetails('company_id'),
            'payment_slip' => 'nullable',
            'description' => 'nullable',
            'amount' => 'required',
            'customer_id' => 'required',
            'transaction_date' => 'required'
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

        // Sanitize customer contract based on estate id
        $datum = $request['contract_ids'];

        $sanitized = [];
        foreach ($datum as $data) {
            $estate_validator = Validator::make($data, [
                'estate_id' => 'required|exists:estates,id,company_id,' . $this->sessionDetails('company_id'),
            ]);

            if ($estate_validator->fails()) {
                return response()->json([
                    'error' => $estate_validator->messages()->first(),
                    'data' => null,
                ], 412);
            }

            $estates = $estate_validator->valid();

            $newContracts = $this->sanitizeContractsByEstateIds($estates, $request->customer_id);

            if (!empty($newContracts)) {
                $sanitized[] = [
                    'estate_id' => $data['estate_id'],
                    'contract_ids' => $newContracts
                ];
            }
        }

        // try to get form payment for each estate
        $total_form_amount = 0;

        foreach ($sanitized as $key => &$sa) {
            $form_amount = $this->generateFormPayment($sa['estate_id']);
            $total_form_amount = $total_form_amount + $form_amount;
            $sa['form_amount'] = $form_amount;
        }

        if ($total_form_amount < 1) {
            return response()->json([
                'error' => 'Error calculating form amount, Please ensure form amount is set for selected estates!',
                'data' => null,
            ], 412);
        }

        if ($request->amount != $total_form_amount) {
            return response()->json([
                'error' => 'Amount does not correspond with selected contract. Amount should be exactly ' . $total_form_amount,
                'data' => null,
            ], 412);
        }

        if (!empty($request->payment_slip)) {
            $filename = $this->uploadFile($request->payment_slip, 'payments');
        }

        $customer = Customer::where('id', $request->customer_id)->first();

        // process receipt for contracts according to estates
        foreach ($sanitized as $key => $contract) {
            $transaction_id = $this->generateTransactionId('FM');
            $customer_contract_ids = implode(',', $contract['contract_ids']);

            $payment = Transaction::updateOrCreate(['estate_id' => $contract['estate_id'], 'customer_id' => $customer->id], [
                // 'customer_contract_ids' => $customer_contract_ids,
                'account_id' => $request->account_id,
                'purpose' => 'form_payment',
                'payment_slip' => $filename ?? null,
                'description' => $request->description,
                'amount' => $contract['form_amount'],
                'staff_id' => auth()->user()->staff->id,
                'type' => 'credit',
                'estate_id' => $contract['estate_id'],
                'customer_id' => $customer->id,
                'transaction_id' => $transaction_id,
                'company_id' => $this->sessionDetails('company_id'),
            ]);

            // if(env('ENT') !== 'local'){
            $receipt = $this->generateFormPaymentReceipt($payment);
            $payment->update(['receipt_location' => $receipt['filepath'] ?? null]);
            $receipts[] = $receipt['filepath'] ?? null;
            // }

            // Move contract to prospect
            foreach ($contract['contract_ids'] as $ids) {
                CustomerContract::where('id', $ids)->update([
                    'status' => 'prospect',
                    'form_payment_transaction_id' => $transaction_id,
                    'transaction_date' => Carbon::parse($request->transaction_date)
                ]);
            }
        }

        if (empty($customer->form_status)) {
            $customer->form_status = 'Not Filled';
            $customer->save();
        }

        // Create customer response form with selected data
        CustomerForm::updateOrCreate(
            ['customer_id' => $customer->id, 'company_id' => $this->sessionDetails('company_id')],
            ['bypass_approval' => null]
        );

        // Log email
        $data['type'] = 'form.payment';
        $data['name'] =  $customer->user->first_name ?? null;
        $data['email'] =  $customer->user->email ?? null;
        $data['attachments'] = $receipts ?? null;

        $this->logEmail($data);

        return response()->json(['message' => 'Success', 'data' => null], 200);
    }

    public function sanitizeContractsByEstateIds($allData, $customer_id)
    {
        // Get all customer contract for this estate
        $leadContracts = CustomerContract::where('customer_id', $customer_id)->where('estate_id', $allData['estate_id'])->where('status', 'lead')->pluck('id')->toArray();
        
        $selected = null;
        if (!empty($allData['contract_id'])) {
            CustomerContract::where('customer_id', $customer_id)
            ->whereIn('id', $leadContracts)
            ->where('estate_id', $allData['estate_id'])
            ->whereNotIn('id', $allData['contract_id'])
            ->delete();
            
            $newContracts = CustomerContract::where('customer_id', $customer_id)->where('estate_id', $allData['estate_id'])->whereIn('id', $allData['contract_id'])->pluck('id')->toArray();

            $selected = $newContracts;
        }

        return $selected;
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
            'primary_marketer_id' => 'required|exists:staff,id,company_id,' . $this->sessionDetails('company_id'),

            'subscribers' => 'nullable',
            'discount' => 'nullable',
            // 'commission' => 'nullable',
        ]);

        if (isset($request->secondary_marketers) && in_array($request->primary_marketer_id, $request->secondary_marketers)) {
            return response()->json([
                'error' => 'Primary Marketer cannot be a secondary marketer at the same time, please check input',
                'data' => null,
            ], 412);
        }

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
            'discount' => $request->discount,
            // 'commission' => $request->commission,
            'subscribers' => $request->has('subscribers') ? $request->subscribers : $CustomerContract->subscribers,
            // 'status' => 'lead',
            'primary_marketer_id' => $request->primary_marketer_id,
            'contract_type' => $request->contract_type,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        $customer = Customer::where('id', $request->customer_id)->first();
        LogService::admin(auth()->user()->id, $customer->user->id, 'Update a customer contract (' . $CustomerContract->id . ')', 'UPDATE');
        return response()->json(['message' => 'Successfully updated', 'data' => ['contract_id' => $CustomerContract->id, 'customer_id' => $request->customer_id]], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerContract $CustomerContract, $id)
    {
        $contract = CustomerContract::where('id', $id)->first();

        if (!$contract) {
            return response()->json(['message' => 'No records found', 'data' => null], 200);
        }

        $customer_id = $contract->customer_id;

        $customer = Customer::where('id', $customer_id)->first();
        LogService::admin(auth()->user()->id, $customer->user->id, 'Update a customer contract (' . $contract->id . ')', 'UPDATE');
        $contract->delete();
        return response()->json(['message' => 'Successfully deleted', 'data' => ['customer_id' => $customer_id]], 200);
    }

    public function singleCustomerFormDetails(Request $request, $customer_id)
    {
        $estates = CustomerContract::distinct('estate_id')->with('estate:id,name,form_amount')->select('estate_id')->where('customer_id', $customer_id)->where('company_id', $this->sessionDetails('company_id'));

        if (!empty($request->status)) {
            $estates = $estates->where('status', $request->status);
        }

        $estates = $estates->get();

        $customer = Customer::with('user:id,first_name,last_name,middle_name')->where('id', $customer_id)->first();
        $customerDetails = $customer->user ?? null;

        $contracts = CustomerContract::with('location:name,id')->where('customer_id', $customer_id)->where('company_id', $this->sessionDetails('company_id'))->select(['id', 'customer_id', 'city_id', 'location_id', 'estate_id', 'property_type', 'status', 'plot_size', 'building_type', 'property_type_id', 'building_type_id', 'contract_type', 'property_type'])->orderBy('created_at', 'DESC');

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
                $details = CustomerContract::with('location:name,id', 'building_type:id,name', 'plot_size:id,size')->where('estate_id', $estate->estate_id)->where('customer_id', $customer_id)->select(['id', 'customer_id', 'city_id', 'location_id', 'estate_id', 'property_type', 'status', 'plot_size', 'building_type', 'contract_type', 'property_type', 'property_type_id'])->orderBy('created_at', 'DESC')->where('company_id', $this->sessionDetails('company_id'));

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
            return response()->json(['message' => 'Success', 'data' => $data], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => []], 200);
        }
    }

    public function formPaymentHistory($customer_id)
    {
        $histories = Transaction::with(['contracts', 'estate:id,name'])->where(['customer_id' => $customer_id, 'company_id' => $this->sessionDetails('company_id'), 'purpose' => 'form_payment'])->whereHas('contracts')->orderBy('created_at', 'DESC')->get();

        $data = [];

        foreach ($histories as $history) {
            $contracts = [];
            foreach ($history->contracts as $sub) {
                $contracts[] = [
                    'id' => $sub->id,
                    'contract_name' => $sub->contract_name,
                    'plot_size' => $sub->plot_size_details,
                    'building_type' => $sub->building_type_details,
                ];
            }

            $data[] = [
                'id' => $history->id,
                'estate_id' => $history->estate_id,
                'customer_id' => $history->customer_id,
                'created_at' => $history->created_at,
                'amount' => $history->amount,
                'estate_id' => $history->estate_id,
                'estate_name' => $history->estate->name,
                'payment_slip' => $history->payment_slip,
                'receipt_location' => $history->receipt_location,
                'purpose' => ucwords(str_replace('_', ' ', $history->purpose)),
                'contracts' => $contracts
            ];
        }

        return response()->json(['message' => 'Success', 'data' => $data], 200);
    }

    public function clear(CustomerContract $contract_id){
        if(empty($contract_id->sei_payment_transaction_id)){
            return response()->json(['error' => 'No commitment made for this contract', 'data' => []], 412);
        }

        if ($contract_id->seInfrastructurePayment->type == 'SE' && $contract_id->seInfrastructurePayment->balance > 0) {
            return response()->json(['error' => 'Not enough commitment made for this contract', 'data' => []], 412);
        }

        $contract_id->update(['contract_status' => 'closed']);
        return response()->json(['message' => 'Success', 'data' => []], 200);
    }
}
