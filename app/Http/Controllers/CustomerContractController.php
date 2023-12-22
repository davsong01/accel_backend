<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Staff;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Models\CustomerContract;
use App\Models\LegalDocumentType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Models\GeneratedLegalDocument;
use Illuminate\Support\Facades\Validator;

class CustomerContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $customer_id)
    {
        $contracts = CustomerContract::with('property_type_details:type,id,estate_price,settings_and_excavation,infrastructural_fee', 'city:name,id', 'estate:name,id', 'location:name,id', 'building_type:id,name', 'plot_size:id,size')->where('customer_id', $customer_id)->where('company_id', $this->sessionDetails('company_id'))->select(['id', 'primary_marketer_id', 'property_type_id', 'customer_id', 'city_id', 'location_id', 'estate_id', 'property_type', 'status', 'plot_size', 'building_type', 'subscribers', 'contract_type', 'property_type', 'transaction_date', 'created_at']);

        if (!empty($request->contract_type)) {
            $contracts = $contracts->where('contract_type', $request->contract_type);
        }
        // ->groupBy('estate_id')
        $contracts = $contracts->orderBy('created_at', 'DESC')
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
                $contract->primary_marketer = $contract->primaryMarketer($contract);
                $contract->subscribers = !empty($contract->subscribers) ? json_decode($contract->subscribers) : '';
                $contract['location'] = $contract->location->name;
                $allContracts[$key] = $contract;

                $contract->generated_legal_documents = $this->generatedDocuments($contract);
                $contract->legal_document_type_ids = $this->prepareDocumentTypeIds($contract);
                $allContracts[$key]['form_amount'] = $this->getContractFormAmount($contract, 'single');
            }

            $data['all_contracts'] = $allContracts;

            return response()->json(['message' => 'Success', 'data' => $data], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => []], 200);
        }
    }

    public function generatedDocuments($contract)
    {
        $generated = GeneratedLegalDocument::with('template')->where('contract_id', $contract->id)->where('customer_id', $contract->customer_id)->get();
        $alldocumts = [];

        foreach ($generated as $generate) {
            $alldocumts[$generate->template->legalDocumentType->name] = [
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
                'primary_marketer_id' => 'nullable|exists:staff,id,company_id,' . $this->sessionDetails('company_id'),
                'secondary_marketers' => 'nullable',
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
                $data['primary_marketer_id'] = $data['primary_marketer_id'];
                $data['secondary_marketers'] = isset($data['secondary_marketers']) && !empty($data['secondary_marketers']) ? json_encode($data['secondary_marketers']) : json_encode([]);

                $this->createCustomerContract($data);
            }

            return response()->json(['message' => 'Success', 'data' => ['customer_id' => $data['customer_id']]], 200);
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

            // Add user
            $user = $this->createUser($data);

            // Add customer

            if ($user) {
                $customer = $this->createCustomer($user);

                // Log email
                $data['type'] = 'user.store';
                $data['name'] =  $user->first_name;
                $data['email'] =  $user->email;
                $data['customer_id'] =  $user->customerId;

                $this->logEmail($data);
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
                    $data2['type'] = 'primary.marketer.email.for.new.contract';
                    $markerter = Staff::where('id', $data['primary_marketer_id'])->first();

                    if ($markerter) {
                        $data2['name'] =  $markerter->user->first_name;
                        $data2['customer_details'] = [
                            'customer_name' => $customer->user->first_name . ' ' . $customer->user->last_name,
                            'customer_phone' => $customer->user->phone . ' ' . $customer->user->phone,
                            'customerId' => $customer->user->customerId,
                        ];
                        $data2['customer_name'] =  $customer->user->first_name;
                        $data2['email'] =  $markerter->user->email;

                        $this->logEmail($data2);
                    }
                }
                // Email Primary Marketer
            } else {
                return response()->json(['message' => 'Could not create customer', 'data' => null], 422);
            }

            return response()->json(['message' => 'Success', 'data' => ['customer_id' => $customer->id]], 200);
        }

        return response()->json(['message' => 'Success', 'data' => null], 200);
    }

    public function createUser($request)
    {
        // Add User
        $additional_details = json_encode([
            'alternate_number' => $request->alternate_number ?? '',
            'alternate_email' => $request->alternate_email ?? '',
            'state' => $request->state ?? '',
            'residential_address' => $request->residential_address ?? '',
            'office_address' => $request->office_address ?? '',
            'marital_status' => $request->marital_status ?? '',
            'nationality' => $request->nationality ?? '',
            'lga' => $request->lga ?? '',
        ]);

        $next_of_kin = json_encode([
            'nok_first_name' => $request->nok_first_name ?? '',
            'nok_last_name' => $request->nok_last_name ?? '',
            'nok_phone_number' => $request->nok_phone_number ?? '',
            'nok_house_address' => $request->nok_house_address ?? '',
            'nok_email_address' => $request->nok_email_address ?? '',
            'nok_relationship' => $request->nok_relationship ?? '',
        ]);

        $user = User::updateOrCreate([
            'first_name' => $request['first_name'],
            'password' => Hash::make($request['password'] ?? '123456789'),
            'email' => $request['email'],
            'gender' => $request['gender'],
            'last_name' => $request['last_name'],
            'phone_number' => $request['phone_number'],
            'additional_details' => $additional_details ?? null,
            'next_of_kin' => $next_of_kin ?? null,
            'company_id' => $this->sessionDetails('company_id'),
            'branch_id' => $this->sessionDetails('branch_id'),
            'email_verified_at' => now(),
            'status' => 'active',
            'type' => 'customer',
            'company_id' => $this->sessionDetails('company_id'),
            'branch_id' => $this->sessionDetails('branch_id'),
        ]);

        $user->update(['customerId' => $this->generateCustomerId(false)]);

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
        ]);

        return;
    }

    /**
     * Display the specified resource.
     */
    public function show($contract_id)
    {
        $contract = CustomerContract::with('property_type_details:type,id,estate_price,settings_and_excavation,infrastructural_fee', 'city:name,id', 'estate:name,id', 'location:name,id', 'building_type:id,name', 'plot_size:id,size')->where('id', $contract_id)->select(['id', 'property_type_id', 'customer_id', 'city_id', 'location_id', 'estate_id', 'property_type', 'status', 'plot_size', 'building_type', 'subscribers', 'contract_type', 'property_type', 'primary_marketer_id'])->orderBy('created_at', 'DESC')->where('company_id', $this->sessionDetails('company_id'))->first();

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

        $customer = Customer::where('id', $request->customer_id)->first();

        if ($form_payment) {
            $receipts = [];
            foreach ($form_payment['explanation'] as $key => $form) {
                $customer_contract_ids = implode(',', $form['contracts']);
                $payment = Transaction::updateOrCreate(['customer_contract_ids' => $customer_contract_ids], [
                    'customer_contract_ids' => $customer_contract_ids,
                    'account_id' => $data['account_id'],
                    'purpose' => 'form_payment',
                    'payment_slip' => $filename ?? null,
                    'description' => $data['description'],
                    'amount' => $form['form_amount'],
                    'staff_id' => auth()->user()->staff->id,
                    'type' => 'credit',
                    'estate_id' => $key,
                    'customer_id' => $data['customer_id'],
                    'company_id' => $this->sessionDetails('company_id'),
                ]);

                $receipt = $this->generateFormPaymentReceipt($payment);

                $payment->update(['receipt_location' => $receipt['filepath'] ?? null]);
                $receipts[] = $receipt['filepath'] ?? null;

                foreach ($form['contracts'] as $contract) {
                    CustomerContract::where('id', $contract)->update([
                        'status' => 'prospect',
                        'transaction_date' => Carbon::parse($request->transaction_date)
                    ]);
                }

                $customer->form_status = 'Not Filled';
                $customer->save();
            }

            // Log email
            $data['type'] = 'form.payment';
            $data['name'] =  $customer->user->first_name ?? null;
            $data['email'] =  $customer->user->email ?? null;
            $data['attachments'] = $receipts;

            $this->logEmail($data);
        }
        return response()->json(['message' => 'Success', 'data' => null], 200);
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

        if (!$contract) {
            return response()->json(['message' => 'No records found', 'data' => null], 200);
        }

        $customer_id = $contract->customer_id;
        $contract->delete();
        return response()->json(['message' => 'Succesfully deleted', 'data' => ['customer_id' => $customer_id]], 200);
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
            return response()->json(['message' => 'Success', 'data' => $data], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => []], 200);
        }
    }

    public function formPaymentHistory($customer_id)
    {
        $histories = Transaction::with('estate:id,name')->where(['customer_id' => $customer_id, 'company_id' => $this->sessionDetails('company_id'), 'purpose' => 'form_payment'])->orderBy('created_at', 'DESC')->get();

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
}
