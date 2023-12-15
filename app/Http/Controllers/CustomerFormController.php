<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Customer;
use Illuminate\Support\Arr;
use App\Models\CustomerForm;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Models\CustomerContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Validator;

class CustomerFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id = null, $paginate = true)
    {
        if (!empty($id)) {
            $forms = CustomerForm::with(['country:id,name', 'state:id,name', 'lga:id,name'])->where('id', $id)->where('company_id', $this->sessionDetails('company_id'))->select('surname', 'title', 'status', 'no_of_contract', 'first_name', 'middle_name', 'last_name', 'gender', 'created_at', 'id', 'marital_status', 'date_of_birth', 'country_id', 'state_id', 'lga_id', 'next_of_kin_information', 'means_of_identification', 'primary_marketer', 'contract_ids', 'other_details', 'customer_id')->first();

            if ($forms) {
                $forms->contract_details = $this->getFormRequestContractDetails($forms);
                $forms->other_details = !empty($forms->other_details) ? json_decode($forms->other_details, true) : [];
            }
            
            return response()->json(['message' => 'Successfully retrieved', 'data' => $forms], 200);
        }

        $forms = CustomerForm::where('company_id', $this->sessionDetails('company_id'))->where('type', $request->type)->select('surname', 'title', 'status', 'no_of_contract', 'first_name', 'middle_name', 'last_name', 'gender', 'created_at', 'id', 'means_of_identification', 'other_details');

        if (\Request::route()->getPrefix() == 'api/customer') {
            $forms = $forms->where('customer_id', auth::user()->customer->id);
        }

        if (!empty($request->status)) {
            $forms = $forms->where('status', $request->status);
        }
        
        if ($paginate && $paginate == true) {
            $forms = $forms->paginate(20)->withQueryString();
        } else {
            $forms = $forms->get();
        }

        foreach ($forms as $form) {
            $means_of_identification = !empty($form->means_of_identification) ? json_decode($form->means_of_identification, true) : [];
            $form['passport_photo'] = !empty($means_of_identification) && isset($means_of_identification['passport_photo']) ?  url('/') . '/uploads/' . base64_encode($means_of_identification['passport_photo']) : "";
            unset($form['means_of_identification']);
            unset($form['other_details']);
        }

        if (!empty($forms)) {
            return response()->json(['message' => 'Successfully retrieved', 'data' => $forms], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => $forms], 200);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    public function approveCustomerFormResponse($id)
    {
        $customer_form = CustomerForm::where('id', $id)->where('company_id', $this->sessionDetails('company_id'))->where('type', 'form2-request')->first();
        
        if (!$customer_form) {
            return response()->json(['message' => 'No records found', 'data' => []], 200);
        }

        if ($customer_form->status == 'declined') {
            return response()->json([
                'error' => 'Application form already declined',
                'data' => $customer_form->customer_id,
            ], 422);
        }

        $customer_form->update(['status' => 'approved']);

        $data['contract_details'] = $this->getFormRequestContractDetails($customer_form, 'approve_contract');
        
        $data['type'] = 'formrequest.approve';
        $data['medium'] = 'email';
        // $data['user'] = Customer::where('id', 'customer_id')->user;
        $data['name'] = $customer_form->first_name ?? '';
        $data['email'] = $customer_form->email_address ?? '';

        $this->logEmail($data);

        return response()->json(['message' => 'Successful', 'data' => ['customer_id' => $customer_form->customer_id]], 200);
    }

    public function declineCustomerFormResponse(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }
        
        $customer_form = CustomerForm::where('id', $id)->where('company_id', $this->sessionDetails('company_id'))->where('type', 'form2-request')->first();

        if (!$customer_form) {
            return response()->json([
                'error' => 'No records found',
                'data' => null,
            ], 422);
        }

        $customer_form->update([
            'status' => 'declined',
            'reason' => $request->reason
        ]);

        $data['contract_details'] = $this->getFormRequestContractDetails($customer_form, 'decline_contract', $request->reason);

        $data['type'] = 'formrequest.decline';
        $data['reason'] = $request->reason;
        $data['medium'] = 'email';
        // $data['user'] = Customer::where('id', 'customer_id')->user;
        $data['name'] = $customer_form->first_name ?? '';
        $data['email'] = $customer_form->email_address ?? '';

        $this->logEmail($data);

        return response()->json(['message' => 'Successful', 'data' => ['customer_id' => $customer_form->customer_id]], 200);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $admin = false)
    {
       
        if (\Request::route()->getPrefix() == 'api/customer') {
            $request['customer_id'] = auth()->user()->customer->id;
        }
        
        $validator = Validator::make($request->all(), [
            'title' => 'nullable',
            'surname' => 'nullable',
            'first_name' => 'nullable',
            'middle_name' => 'nullable',
            'last_name' => 'nullable',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'nullable',
            'email_address' => 'required',

            // Contact details
            'phone_number' => 'nullable',
            'house_address' => 'nullable',
            'alternate_number' => 'nullable',

            'lga_id' => 'required|exists:lgas,id',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',

            // Next of kin information
            'nok_first_name' => 'nullable',
            'nok_last_name' => 'nullable',
            'nok_house_address' => 'nullable',
            'nok_phone_number' => 'nullable',
            'nok_email_address' => 'nullable',
            'nok_relationship' => 'nullable',

            // Means of identification
            'occupation' => 'nullable',
            'place_of_work' => 'nullable',
            'id_card_type' => 'nullable', // multiple
            'passport_photo' => 'nullable|image|max:2048',
            'id_card' => 'nullable|image|max:2048',
            'signature' => 'nullable|image|max:2048',

            // 'bypass_approval' => 'nullable',
            'marital_status' => 'nullable',
            'customer_id' => 'required|exists:customers,id,company_id,' . $this->sessionDetails('company_id'),

            // Contracts
            'contract' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $customer_contract_ids = [];
        if (!empty($request->contract)) {

            $contracts = $this->cleanJsonInput($request->contract);

            foreach ($contracts as $cont) {
                // Make each contract a request
                $contract_validator = Validator::make($cont, [
                    'estate_id' => 'required|exists:estates,id,company_id,' . $this->sessionDetails('company_id'),
                    'city_id' => 'required|exists:cities,id,company_id,' . $this->sessionDetails('company_id'),
                    'location_id' => 'required|exists:locations,id,company_id,' . $this->sessionDetails('company_id'),

                    'property_type_id' => [
                        'required', Rule::exists('estate_property_type_details', 'id')->where(function ($query) use ($cont) {
                            $query->where('company_id', $this->sessionDetails('company_id'))->where('estate_id', $cont['estate_id'])->where('type', $cont['property_type']);
                        })
                    ],
                    // Other details
                    'property_price' => 'nullable',
                    'infrastuctural_fee' => 'nullable',
                    // 'net_property_fee' => 'nullable',
                    'setting_and_excavation_fee' => 'nullable',
                    // 'discount' => 'nullable',
                    'description' => 'nullable',

                    // Marketer
                    'primary_marketer' => 'nullable|exists:staff,id,company_id,' . $this->sessionDetails('company_id'),
                    'secondary_marketers' => 'nullable',
                    'subscribers' => 'nullable',

                    'property_type' => 'required|in:land,building',
                    'building_type_id' => 'nullable|exists:building_types,id',
                    'plot_size_id' => 'nullable|exists:plot_sizes,id',
                    'branch_id' => 'nullable|exists:branches,id,company_id,' . $this->sessionDetails('company_id'),
                    'contract_type' => 'nullable',
                    'transaction_date' => 'nullable',

                    'contract_id' => 'required|exists:customer_contracts,id,company_id,' . $this->sessionDetails('company_id'),
                ]);

                if ($contract_validator->fails()) {
                    return response()->json([
                        'error' => $contract_validator->messages()->first(),
                        'data' => null,
                    ], 412);
                }


                $update = $validator->valid();
                $update['contract'] = $cont;
                $cont['transaction_date'] = Carbon::parse($request->transaction_date);
                
                // Update the contract
                $customer_contract_ids[] = $cont['contract_id'];
                app('App\Http\Controllers\CustomerContractController')->updateCustomerContract($cont, $cont['contract_id'], $request->customer_id);
            }
        }

        $data = array_merge($validator->valid());
        $data['date_of_birth'] = !empty($data['date_of_birth']) ? Carbon::parse($data['date_of_birth']) : '';

        if (!empty($request->passport_photo)) {
            $data['passport_photo'] = $this->uploadFile($request->passport_photo, 'avatars');
        }

        if (!empty($request->id_card)) {
            $data['id_card'] = $this->uploadFile($request->id_card, 'idcards');
        }

        if (!empty($request->signature)) {
            $data['signature'] = $this->uploadFile($request->signature, 'signatures');
        }

        if (!empty($request->secondary_marketers)) {
            $data['secondary_marketers'] = json_encode($request->secondary_marketers);
        }


        // To be used in customer form
        $data['next_of_kin_information'] = json_encode([
            'nok_first_name' => $data['nok_first_name'] ?? null,
            'nok_last_name' => $data['nok_last_name'] ?? null,
            'nok_house_address' => $data['nok_house_address'] ?? null,
            'nok_phone_number' => $data['nok_phone_number'] ?? null,
            'nok_email_address' => $data['nok_email_address'] ?? null,
            'nok_relationship' => $data['nok_relationship'] ?? null,
        ]);

        $data['means_of_identification'] = json_encode([
            'occupation' => $data['occupation'] ?? null,
            'place_of_work' => $data['place_of_work'] ?? null,
            'id_card_type' => $data['id_card_type'] ?? null, // multiple
            'passport_photo' => $data['passport_photo'] ?? null,
            'id_card' => $data['id_card'] ?? null,
            'signature' => $data['signature'] ?? null,
        ]);

        $data['other_details'] = json_encode([
            'property_price' => $data['property_price'] ?? null,
            'infrastuctural_fee' => $data['infrastuctural_fee'] ?? null,
            'net_property_fee' => $data['net_property_fee'] ?? null,
            'setting_and_excavation_fee' => $data['setting_and_excavation_fee'] ?? null,
            'discount' => $data['discount'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        $data['contact_details'] = json_encode([
            'phone_number' => $data['phone_number'] ?? null,
            'house_address' => $data['house_address'] ?? null,
            'alternate_phone_number' => $data['alternate_number'] ?? null,
        ]);

        // End to be used in customer form

        $data['company_id'] = $this->sessionDetails('company_id');
        if (\Request::route()->getPrefix() == 'api/customer') {
            $data['type'] = 'customer-response';
            $data['status'] = 'pending';
        } else {
            $data['type'] = 'form2-request';
            $data['status'] = 'pending';
        }

        $data['date_of_birth'] = Carbon::parse($request->date_of_birth);
        // Update user
        $customer = Customer::where('id', $data['customer_id'])->first();
        $user = $customer->user;
        
        // Update user
        app('App\Http\Controllers\CustomerController')->updateUser($user, $data);
        $data['raw'] = json_encode($request->all());
        $data['contract_ids'] = json_encode($customer_contract_ids);
        $data['no_of_contract'] = count($customer_contract_ids);

        $data['company_id'] = $this->sessionDetails('company_id');

        $response = Arr::only($data, ['contract_ids', 'customer_id', 'title', 'type', 'status', 'no_of_contract', 'surname', 'first_name', 'middle_name', 'last_name', 'gender', 'date_of_birth', 'marital_status', 'lga_id', 'country_id', 'state_id', 'email_address', 'contact_details', 'next_of_kin_information', 'means_of_identification', 'branch_id', 'other_details', 'primary_marketer', 'secondary_marketers', 'raw', 'company_id', 'no_of_contract', 'contact_details', 'no_of_contracts']);

        CustomerForm::updateOrCreate(
            ['customer_id' => $response['customer_id'], 'company_id' => $response['company_id']],
            $response
        );

        return response()->json(['message' => 'Success', 'data' => []], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerForm $CustomerForm)
    {
        dd($customerForm);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerForm $CustomerForm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerForm $CustomerForm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerForm $CustomerForm)
    {
        //
    }
}
