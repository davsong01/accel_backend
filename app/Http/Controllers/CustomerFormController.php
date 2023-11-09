<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Models\CustomerForm;
use Illuminate\Validation\Rule;
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
            $forms = CustomerForm::where('company_id', $this->sessionDetails('company_id'))->first();
            return response()->json(['message' => 'Successfully retrieved', 'data' => $forms], 200);
        }

        $forms = CustomerForm::where('company_id', $this->sessionDetails('company_id'))->where('type', $request->type);
        
        if (\Request::route()->getPrefix() == 'api/customer'){
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $admin=false)
    {
    // "contract":[
    //     {
    //         "property_price":"kjkkk",
    //         "infrastuctural_fee":"kjkkk",
    //         "net_property_fee":"kjkkk",
    //         "setting_and_excavation_fee":"kjkkk",
    //         "discount":"kjkkk",
    //         "transaction_date":"kjkkk",
    //         "property_type_id":"52",
    //         "property_type":"land",
    //         "building_type":2,
    //         "plot_size":19,
    //         "branch_id":2,
    //         "contract_type":"joint", // joint,single
    //         "contract_id":46,
    //         "estate_id":144,
    //         "subscribers":[
    //             {
    //                 "s_name":"Subscriber 1",
    //                 "s_phone":"077236235723",
    //                 "s_email":"sub1@gmail.com",
    //                 "s_relationship":"Brother"
    //             },
    //             {
    //                 "s_name":"Subscriber 2",
    //                 "s_phone":"0918237232332",
    //                 "s_email":"sub2@gmail.com",
    //                 "s_relationship":"Mother"
    //             }
    //         ],
    //         "primary_marketer":1,
    //         "secondary_marketers":[
    //             {
    //                 "name":"marketer name",
    //                 "secondary_marketer_id":2,
    //                 "percentage":10
    //             },
    //             {
    //                 "name":"Marketer 2 name",
    //                 "secondary_marketer_id":4,
    //                 "percentage":4
    //             }
    //         ]
    //     }
    // ]


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

            'lga_id' => 'required|exists:lgas,id',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',

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

            // 'bypass_approval' => 'nullable',
            'marital_status' => 'nullable',
            'location_id' => 'required|exists:locations,id,company_id,' . $this->sessionDetails('company_id'),
            'customer_id' => 'required|exists:customers,id,company_id,' . $this->sessionDetails('company_id'),

            // Contracts
            'contract' => 'required',
        ]);

        if(!empty($request->contract)){
            foreach($request->contract as $cont){
                $validator = Validator::make($cont, [
                    
                    'estate_id' => 'required|exists:estates,id,company_id,' . $this->sessionDetails('company_id'),
                    'property_type_id' => [
                        'required', Rule::exists('estate_property_type_details', 'id')->where(function ($query) use ($cont) {
                            $query->where('company_id', $this->sessionDetails('company_id'))->where('estate_id', $cont['estate_id'])->where('type', $cont['property_type']);
                        })
                    ],
                    // Other details
                    'property_price' => 'nullable',
                    'infrastuctural_fee' => 'nullable',
                    'net_property_fee' => 'nullable',
                    'setting_and_excavation_fee' => 'nullable',
                    'discount' => 'nullable',
                    'description' => 'nullable',

                    // Marketer
                    'primary_marketer' => 'nullable|exists:staff,id,company_id,' . $this->sessionDetails('company_id'),
                    'secondary_marketers' => 'nullable',
                    'subscribers' => 'nullable',
                    'transaction_date' => 'nullable',

                    'property_type' => 'required|in:land,building',
                    'building_type_id' => 'nullable|exists:building_types,id',
                    'plot_size_id' => 'nullable|exists:plot_sizes,id',
                    'branch_id' => 'nullable|exists:branches,id,company_id,' . $this->sessionDetails('company_id'),
                    'contract_type' => 'nullable',
                
                    'contract_id' => 'required|exists:customer_contracts,id,company_id,' . $this->sessionDetails('company_id'),
                ]);
            }

        }

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }
        $data = $validator->valid();
        dd($data);
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
            'next_of_kin_firstname', 'next_of_kin_lastname', 'next_of_kin_house_address', 'next_of_kin_phone_number', 'next_of_kin_email_address', 'next_of_kin_relationship', 'phone_number', 'house_address', 'alternate_phone_number', 'occupation', 'place_of_work', 'id_card_type', 'passport_photo', 'id_card', 'signature', 'property_price', 'infrastuctural_fee', 'net_property_fee', 'setting_and_excavation_fee', 'discount', 'description'
        ]);

        if (\Request::route()->getPrefix() == 'api/customer') {
            $data['type'] = 'customer-response';
        }else{
            $data['type'] = 'form2-request';
            $data['status'] = 'completed'; 
        }
        
        CustomerForm::updateOrCreate(
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
    public function show(CustomerForm $CustomerForm)
    {
        //
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
