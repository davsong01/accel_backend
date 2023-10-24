<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Models\CustomerFormTwo;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Validator;

class CustomerFormTwoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'alternate_phone_number' => 'nullable',

            // Next of kin information
            'next_of_kin_firstname' => 'nullable',
            'next_of_kin_lastname' => 'nullable',
            'next_of_kin_house_address' => 'nullable',
            'next_of_kin_phone_number' => 'nullable',
            'next_of_kin_email_address' => 'nullable',
            'next_of_kin_relationship' => 'nullable',

            // Means of identification
            'occupation' => 'nullable',
            'place_of_work' => 'nullable',
            'id_card_type' => 'nullable', // multiple
            'passport_photo' => 'nullable|image|max:2048',
            'id_card' => 'nullable|image|max:2048',
            'signature' => 'nullable|image|max:2048',

            // Other details
            'property_price' => 'nullable',
            'infrastuctural_fee' => 'nullable',
            'net_property_fee' => 'nullable',
            'setting_and_excavation_fee' => 'nullable',
            'discount' => 'nullable',
            'description' => 'nullable',
            
            // Marketer
            'marketers' => 'nullable',
            'transaction_date' => 'nullable',
            'bypass_approval' => 'nullable',
            'marital_status' => 'nullable',
            'estate_id' => 'required|exists:estates,id,company_id,' . $this->sessionDetails('company_id'),
            'property_type_id' => [
                'required', Rule::exists('estate_property_type_details', 'id')->where(function ($query) use ($request) {
                    $query->where('company_id', $this->sessionDetails('company_id'))->where('estate_id', $request->estate_id)->where('type', $request->property_type);
                })
            ],
            'property_type' => 'required|in:land,building',
            'building_type_id' => 'nullable|exists:building_types,id',
            'plot_size_id' => 'nullable|exists:plot_sizes,id',
            'branch_id' => 'nullable|exists:branches,id,company_id,' . $this->sessionDetails('company_id'),
            'contract_type' => 'nullable',
            'lga_id' => 'required|exists:lgas,id',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'location_id' => 'required|exists:locations,id,company_id,' . $this->sessionDetails('company_id'),
            'customer_id' => 'required|exists:customers,id,company_id,' . $this->sessionDetails('company_id'),
            'customer_contract_id' => 'required|exists:customer_contracts,id,company_id,' . $this->sessionDetails('company_id'),

        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }
        $data = $validator->valid();
        $data['date_of_birth'] = !empty($data['date_of_birth']) ? Carbon::parse($data['date_of_birth']) : '';
        
        if(!empty($request->passport_photo)){
            $data['passport_photo'] = $this->uploadFile($request->passport_photo, 'avatars');
        }

        if (!empty($request->id_card)) {
            $data['id_card'] = $this->uploadFile($request->id_card, 'idcards');
        }

        if (!empty($request->signature)) {
            $data['signature'] = $this->uploadFile($request->signature, 'signatures');
        }

        $data['contact_details'] = json_encode([
            'phone_number' => $data['phone_number'] ?? null,
            'house_address' => $data['house_address'] ?? null,
            'alternate_phone_number' => $data['alternate_phone_number'] ?? null,
        ]);

        $data['next_of_kin_information'] = json_encode([
            'next_of_kin_firstname' => $data['next_of_kin_firstname'] ?? null,
            'next_of_kin_lastname' => $data['next_of_kin_lastname'] ?? null,
            'next_of_kin_house_address' => $data['next_of_kin_house_address'] ?? null,
            'next_of_kin_phone_number' => $data['next_of_kin_phone_number'] ?? null,
            'next_of_kin_email_address' => $data['next_of_kin_email_address'] ?? null,
            'next_of_kin_relationship' => $data['next_of_kin_relationship'] ?? null,
        ]);
        
        $data['means_of_identification'] = json_encode([
            'occupation' => $data['nullable'] ?? null,
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
        $data['company_id'] = $this->sessionDetails('company_id');
        
        $data = Arr::except($data, [
            'next_of_kin_firstname','next_of_kin_lastname','next_of_kin_house_address','next_of_kin_phone_number','next_of_kin_email_address','next_of_kin_relationship','phone_number','house_address','alternate_phone_number','occupation','place_of_work','id_card_type','passport_photo','id_card','signature','property_price','infrastuctural_fee','net_property_fee','setting_and_excavation_fee','discount','description'
        ]);

        CustomerFormTwo::updateOrCreate(
            [
                'customer_id' => $data['customer_id'],
                'customer_contract_id' => $data['customer_contract_id'],
                'company_id' => $data['company_id']
            ],
            $data
        );

        return response()->json(['message' => 'success', 'data' => null], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerFormTwo $customerFormTwo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerFormTwo $customerFormTwo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerFormTwo $customerFormTwo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerFormTwo $customerFormTwo)
    {
        //
    }
}

// title: nullable
// surname : ''
// first_name : kjkkk
// middle_name : kjkkk
// last_name : kjkkk
// gender : female
// data_of_birth : 2002/10/12
// email_address : test2@gmail.com

// // Contact details:
// phone_number : kjkkk
// house_address : kjkkk
// alternate_phone_number : kjkkk
// // Next of kin information:
// next_of_kin_firstname : kjkkk
// next_of_kin_lastname : kjkkk
// next_of_kin_house_address : kjkkk
// next_of_kin_phone_number : kjkkk
// next_of_kin_email_address : kjkkk
// next_of_kin_relationship : kjkkk
// // Means of identification:
// occupation : kjkkk
// place_of_work : kjkkk
// id_card_type : nullable // multiple:
// passport_photo : kjkkk
// id_card : kjkkk
// signature : kjkkk
// // Other details:
// property_price : kjkkk
// infrastuctural_fee : kjkkk
// net_property_fee : kjkkk
// setting_and_excavation_fee : kjkkk
// discount : kjkkk
// description : kjkkk
// // Marketer:
// marketers : kjkkk
// transaction_date : kjkkk
// bypass_approval : kjkkk
// date_of_birth : kjkkk
// marital_status : kjkkk
// property_type_id : ''
// property_type : ''
// building_type_id : ''
// plot_size_id : ''
// branch_id : ''
// contract_type: ''
// lga_id:''
// country_id:''
// state_id:''
// location_id:''
// customer_id:508
// customer_contract_id:''
