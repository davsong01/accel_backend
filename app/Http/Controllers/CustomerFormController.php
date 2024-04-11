<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Customer;
use Illuminate\Support\Arr;
use App\Models\CustomerForm;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CompanySetting;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Models\CustomerContract;
use App\Services\LogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Validator;

class CustomerFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $id = null, $customer_id = null)
    {
        // Returns customer details and contract details for admin
        $forms = CustomerForm::with(['customer'])->where('company_id', $this->sessionDetails('company_id'));
        
        if (\Request::route()->getPrefix() == 'api/customer') {

            if(Auth::check() ){
                $customer_id = Auth::user()->customer->id ?? null;
                $forms = $forms->where('customer_id', $customer_id)->first();
                
                $forms->contract_details = $this->formatResponseForm($forms);
            }
            return response()->json(['message' => 'Successfully retrieved', 'data' => $forms], 200);
        }
        
        if (!empty($id)) {
            $forms = $forms->where('id', $id)->first();

            if ($forms) {
                $forms->contract_details = $this->formatResponseForm($forms);
            }

            return response()->json(['message' => 'Successfully retrieved', 'data' => $forms], 200);

        }

        if (!empty($request->type)) {
            if($request->type == 'form2-request'){
                // $forms = $forms->where('type', $request->type)->where('customer_form_status', 'approved');
                $forms = $forms->where('type', $request->type)->where('customer_form_status', 'approved');
            }else{
                $forms = $forms->where('type', $request->type);
            }
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
            $form->contract_details = $this->formatResponseForm($form);
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

    /*****
     * Nudge customer to re fill customer form
     */

    public function nudgeCustomer($customer_id){
        $customer = Customer::with('user')->where('id', $customer_id)->first();
        $data['type'] = 'nudge.customer';
        $data['medium'] = 'email';
        // $data['user'] = Customer::where('id', 'customer_id')->user;
        $data['name'] = $customer->user->first_name;
        $data['customerId'] = $customer->user->customerId;
        $data['email'] = $customer->user->email;
        
        $this->logEmail($data);

        return response()->json(['message' => 'Customer nudged successfully', 'data' => []], 200);

    }

    public function nudgeAdmin($form_id)
    {
        $notifiableIds = [1];
        $form = CustomerForm::where('id', $form_id)->first();
       
        $staffs = Staff::whereIn('id',$notifiableIds)->get();
        
        foreach($staffs as $staff){
            $data['type'] = 'nudge.admin';
            $data['medium'] = 'email';
            // $data['user'] = Customer::where('id', 'customer_id')->user;
            $data['name'] = $staff->user->first_name;
            $data['email'] = $staff->user->email;
            $data['form'] = $form;
    
            $this->logEmail($data);
            $data = [
                'title' => 'Your Attention is needed for: '.$form->form_name,
                'content' => 'Could you please take a moment to look into the form response: ' . $form->form_name,
                'link' => $this->sessionDetails('company_url') . '/customer-form-response/' . $form->id,
                'user_type' => 'staff',
                'staff_id' => $staff->id,
                'company_id' => $this->sessionDetails('company_id')
            ];

            $this->notify($data);
        }


        return response()->json(['message' => 'Admin nudged successfully', 'data' => []], 200);
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

        $data['contract_details'] = $this->formatResponseForm($customer_form, 'approve_contract');

        $data['type'] = 'formrequest.approve';
        $data['medium'] = 'email';
        // $data['user'] = Customer::where('id', 'customer_id')->user;
        $data['name'] = $customer_form->first_name ?? '';
        $data['email'] = $customer_form->email_address ?? '';

        $this->logEmail($data);

        LogService::admin(auth()->user()->id, $customer_form->customer->user->id, 'Approve customer form 2 request (' . $customer_form->id .')', 'UPDATE');
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
            'form_status' => 'Declined',
        ]);

        $data['contract_details'] = $this->formatResponseForm($customer_form, 'decline_contract', $request->reason);

        $data['type'] = 'formrequest.decline';
        $data['reason'] = $request->reason;
        $data['medium'] = 'email';
        $data['name'] = $customer_form->first_name ?? '';
        $data['email'] = $customer_form->email_address ?? '';

        $this->logEmail($data);

        LogService::admin(auth()->user()->id, $customer_form->customer->user->id, 'Decline customer form 2 request (' . $customer_form->id .')', 'UPDATE');
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

        $customer_form->update([
            'customer_form_status' => 'approved',
            'type' => 'form2-request'
        ]);
        
        // $data['contract_details'] = $this->getFormRequestContractDetails($customer_form, 'approve_contract');

        // $data['type'] = 'formrequest.approve';
        // $data['medium'] = 'email';
        // // $data['user'] = Customer::where('id', 'customer_id')->user;
        // $data['name'] = $customer_form->first_name ?? '';
        // $data['email'] = $customer_form->email_address ?? '';

        // $this->logEmail($data);

        LogService::admin(auth()->user()->id, $customer_form->customer->user->id, 'Approve customer form 2 response (' . $customer_form->id .')', 'UPDATE');

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
            'type' => 'customer-response'
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
        LogService::admin(auth()->user()->id, $customer_form->customer->user->id, 'Decline customer form 2 response (' . $customer_form->id .')', 'UPDATE');
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
                    'property_price' => 'nullable|numeric',
                    'infrastuctural_fee' => 'nullable|numeric',
                    'setting_and_excavation_fee' => 'nullable',
                    'discount' => 'nullable|numeric',
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
        $data['date_of_birth'] = Carbon::parse($request->date_of_birth);
       
        // Update user
        $customer = Customer::where('id', $data['customer_id'])->first();
        $user = $customer->user;
        app('App\Http\Controllers\CustomerContractController')->updateUser($user, $data);

        // Update customer contract
        if (\Request::route()->getPrefix() == 'api/customer') {
            $data['type'] = 'customer-response';
            $data['status'] = 'pending';
        } else {
            $data['type'] = 'form2-request';
            $data['status'] = 'pending';
        }

        if (\Request::route()->getPrefix() != 'api/customer') {
            $customer->response_form->update([
                // 'customer_form_status' => 'approved',
            ]);
        }
        
        $customer->response_form->update([
            'type' => $data['type'],
            'status' => $data['status'],
        ]);

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

        // Log email for admin
        if (\Request::route()->getPrefix() == 'api/customer') {
            $notifiableIds = [1];
            $notify = Staff::whereIn('id', $notifiableIds)->get();
    
            foreach($notify as $staff){
                $data['type'] = 'nudge.admin';
                $data['medium'] = 'email';
 
                $data['name'] = $staff->user->first_name;
                $data['email'] = $staff->user->email;
                $data['form'] = $customer->response_form;

                $this->logEmail($data);
                $data = [
                    'title' => 'Your Attention is needed for: ' . $customer->response_form->form_name,
                    'content' => 'Could you please take a moment to look into the form response: ' . $customer->response_form->form_name,
                    'link' => $this->sessionDetails('company_url') . '/customer-form-response/' . $customer->response_form->id,
                    'user_type' => 'staff',
                    'staff_id' => $staff->id,
                    'company_id' => $this->sessionDetails('company_id')
                ];

                $this->notify($data);
            }

        }
        
        LogService::admin(auth()->user()->id, $customer->user->id, 'Update customer form (' . $customer->response_form->id .')', 'UPDATE');
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
