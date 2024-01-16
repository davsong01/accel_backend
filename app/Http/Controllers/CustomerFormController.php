<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Support\Arr;
use App\Models\CustomerForm;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CompanySetting;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Models\CustomerContract;
use Illuminate\Support\Facades\Auth;
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
        // Returns customer details and contract details for admin
        $forms = CustomerForm::with(['customer'])->where('company_id', $this->sessionDetails('company_id'));
        
        if (\Request::route()->getPrefix() == 'api/customer') {
            if(Auth::check() ){
                dd(Auth::user());
                $customer_id = Auth::user()->customer->id ?? null;
                $forms = $forms->where('customer_id', $customer_id)->first();
                $forms->contract_details = $this->formatResponseForm($forms);
            }
            
            return response()->json(['message' => 'Successfully retrieved', 'data' => $forms], 200);
        }
        
        if (!empty($id)) {
            $forms = $forms->where('id')->first();
            if ($forms) {
                $forms->contract_details = $this->formatResponseForm($forms);
            }

            return response()->json(['message' => 'Successfully retrieved', 'data' => $forms], 200);

        }

        // if(!empty($customer_id)){
        //     $forms = $forms->where('customer_id', $customer_id)->first();
        //     if ($forms) {
        //         $forms->contract_details = $this->formatResponseForm($forms);
        //     }

        //     return response()->json(['message' => 'Successfully retrieved', 'data' => $forms], 200);

        // }

        if (!empty($request->type)) {
            $forms = $forms->where('type', $request->type);
        }
       
        if (!empty($request->status)) {
            $forms = $forms->where('status', $request->status);
        }

        if ($request->paginate && $request->paginate === true) {
            $forms = $forms->paginate(20)->withQueryString();
        } else {
            $forms = $forms->get();
        }

        foreach ($forms as $form) {
            $means_of_identification = !empty($form->means_of_identification) ? json_decode($form->means_of_identification, true) : [];
            $form['passport_photo'] = !empty($means_of_identification) && isset($means_of_identification['passport_photo']) ?  $this->encodeGeneralUploadedFile($means_of_identification['passport_photo']) : "";
            unset($form['means_of_identification']);
            unset($form['other_details']);

            $form->contract_details = $this->getFormRequestContractDetails($form);
            $form->other_details = !empty($forms->other_details) ? json_decode($form->other_details, true) : [];
        }

        if (!empty($forms)) {
            return response()->json(['message' => 'Successfully retrieved', 'data' => $forms], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => $forms], 200);
        }
    }
    // public function index(Request $request, $id = null, $paginate = true)
    // {
        
    //     if (!empty($id)) {
    //         $forms = CustomerForm::with(['country:id,name', 'state:id,name', 'lga:id,name'])->where('id', $id)->where('company_id', $this->sessionDetails('company_id'))->select('surname', 'title', 'status', 'customer_form_status', 'no_of_contract', 'first_name', 'middle_name', 'last_name', 'gender', 'created_at', 'id', 'marital_status', 'date_of_birth', 'country_id', 'state_id', 'lga_id', 'next_of_kin_information', 'means_of_identification', 'primary_marketer', 'contract_ids', 'other_details', 'customer_id')->first();
    //         dd($forms);
    //         if ($forms) {
    //             $forms->contract_details = $this->getFormRequestContractDetails($forms);
    //             $forms->other_details = !empty($forms->other_details) ? json_decode($forms->other_details, true) : [];
    //         }
    //         return response()->json(['message' => 'Successfully retrieved', 'data' => $forms], 200);
    //     }

    //     $forms = CustomerForm::with(['country:id,name', 'state:id,name', 'lga:id,name'])->where('company_id', $this->sessionDetails('company_id'));

    //     if (!empty($request->type)) {
    //         $forms = $forms->where('type', $request->type);
    //     }
        
    //     $forms = $forms->select('surname', 'title', 'status', 'customer_form_status', 'no_of_contract', 'first_name', 'middle_name', 'last_name', 'gender', 'created_at', 'id', 'means_of_identification', 'other_details');

    //     if (\Request::route()->getPrefix() == 'api/customer') {
    //         $forms = $forms->where('customer_id', Auth::user()->customer->id);
    //     }

    //     if (!empty($request->status)) {
    //         $forms = $forms->where('status', $request->status);
    //     }

    //     if ($request->paginate && $request->paginate === true) {
    //         $forms = $forms->paginate(20)->withQueryString();
    //     } else {
    //         $forms = $forms->get();
    //     }

    //     foreach ($forms as $form) {
    //         $means_of_identification = !empty($form->means_of_identification) ? json_decode($form->means_of_identification, true) : [];
    //         $form['passport_photo'] = !empty($means_of_identification) && isset($means_of_identification['passport_photo']) ?  $this->encodeGeneralUploadedFile($means_of_identification['passport_photo']) : "";
    //         unset($form['means_of_identification']);
    //         unset($form['other_details']);

    //         $form->contract_details = $this->getFormRequestContractDetails($form);
    //         $form->other_details = !empty($forms->other_details) ? json_decode($form->other_details, true) : [];
    //     }

    //     if (!empty($forms)) {
    //         return response()->json(['message' => 'Successfully retrieved', 'data' => $forms], 200);
    //     } else {
    //         return response()->json(['message' => 'No records found', 'data' => $forms], 200);
    //     }
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    public function approveForm2Request($id)
    {
        $customer_form = CustomerForm::with('customer')->where('id', $id)->where('company_id', $this->sessionDetails('company_id'))->where('type', 'form2-request')->first();
    
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
        $customer_form->customer->update([
            'form_status' => 'Confirmed',
        ]);

        $data['contract_details'] = $this->getFormRequestContractDetails($customer_form, 'approve_contract');

        $data['type'] = 'formrequest.approve';
        $data['medium'] = 'email';
        // $data['user'] = Customer::where('id', 'customer_id')->user;
        $data['name'] = $customer_form->first_name ?? '';
        $data['email'] = $customer_form->email_address ?? '';

        $this->logEmail($data);

        return response()->json(['message' => 'Successful', 'data' => ['customer_id' => $customer_form->customer_id]], 200);
    }

    public function declineForm2Request(Request $request, $id)
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

        $customer_form = CustomerForm::with('customer')->where('id', $id)->where('company_id', $this->sessionDetails('company_id'))->where('type', 'form2-request')->first();

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

        $customer_form->customer->update([
            'form_status' => 'Unconfirmed',
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


    public function approveCustomerFormResponse($id)
    {
        $customer_form = CustomerForm::with('customer')->where('id', $id)->where('company_id', $this->sessionDetails('company_id'))->where('type', 'customer-response')->first();
        
        if (!$customer_form) {
            return response()->json(['message' => 'No record found', 'data' => []], 200);
        }

        if ($customer_form->customer_form_status == 'declined') {
            return response()->json([
                'error' => 'Customer form already declined',
                'data' => $customer_form->customer_id,
            ], 422);
        }

        $customer_form->update(['customer_form_status' => 'approved']);
        
        // $data['contract_details'] = $this->getFormRequestContractDetails($customer_form, 'approve_contract');

        // $data['type'] = 'formrequest.approve';
        // $data['medium'] = 'email';
        // // $data['user'] = Customer::where('id', 'customer_id')->user;
        // $data['name'] = $customer_form->first_name ?? '';
        // $data['email'] = $customer_form->email_address ?? '';

        // $this->logEmail($data);

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

        $customer_form = CustomerForm::with('customer')->where('id', $id)->where('company_id', $this->sessionDetails('company_id'))->where('type', 'customer-response')->first();

        if (!$customer_form) {
            return response()->json([
                'error' => 'No record found',
                'data' => null,
            ], 422);
        }

        $customer_form->update([
            'customer_form_status' => 'declined',
        ]);

        // $customer_form->customer->update([
        //     'form_status' => 'Unconfirmed',
        // ]);

        // $data['contract_details'] = $this->getFormRequestContractDetails($customer_form, 'decline_contract', $request->reason);

        // $data['type'] = 'formrequest.decline';
        // $data['reason'] = $request->reason;
        // $data['medium'] = 'email';
        // // $data['user'] = Customer::where('id', 'customer_id')->user;
        // $data['name'] = $customer_form->first_name ?? '';
        // $data['email'] = $customer_form->email_address ?? '';

        // $this->logEmail($data);
        
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

            'lga_id' => 'nullable|exists:lgas,id',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'nullable|exists:states,id',

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

            // Additional details
            'title' => 'nullable',
            "alternate_number" => 'nullable',
            "alternate_email" => 'nullable',
            "residential_address" => 'nullable',
            "office_address" => 'nullable',
            "marital_status" => 'nullable',

            // 'bypass_approval' => 'nullable',
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

        // Update customer
        $data['next_of_kin_information'] = json_encode([
            'nok_first_name' => $data['nok_first_name'] ?? null,
            'nok_last_name' => $data['nok_last_name'] ?? null,
            'nok_house_address' => $data['nok_house_address'] ?? null,
            'nok_phone_number' => $data['nok_phone_number'] ?? null,
            'nok_email_address' => $data['nok_email_address'] ?? null,
            'nok_relationship' => $data['nok_relationship'] ?? null,
        ]);

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
        
        app('App\Http\Controllers\CustomerContractController')->updateUser($user, $data);
       
        // update customer form status
        $customer->update([
            'occupation' => $data['occupation'] ?? $customer->occupation,
            'place_of_work' => $data['place_of_work'] ?? $customer->place_of_work,
            'id_card_type' => $data['id_card_type'] ?? $customer->id_card_type,
            'id_card' => $data['id_card'] ?? $customer->id_card,
            'signature' => $data['signature'] ?? $customer->signature,
            'office_address' => $data['office_address'] ?? $customer->office_address,
            'form_status' => 'Unconfirmed'
        ]);

        return response()->json(['message' => 'Success', 'data' => []], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerForm $CustomerForm)
    {
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
