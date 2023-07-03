<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CompanySetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class CompanySettingController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanySetting $companySetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanySetting $companySetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCompanyProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'company_logo' => 'sometimes',
           'main_color' => 'sometimes',
           'primary_color' => 'sometimes',
           'secondary_color' => 'sometimes',
           'company_name' => 'sometimes',
           'company_email' => 'sometimes',
           'country' => 'sometimes',
           'currency' => 'sometimes',
           'address' => 'sometimes',
            'about' => 'sometimes',
           'website' => 'sometimes|url',
           'twitter' => 'sometimes|url',
           'facebook' => 'sometimes|url',
           'linkedin' => 'sometimes|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $companySetting = CompanySetting::where('company_id', $this->sessionDetails('company_id'))->first();
        
        $data = [
            'main_color' => $request->main_color,
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'company_name' => $request->company_name,
            'company_email' => $request->company_email,
            'country' => $request->country,
            'currency' => $request->currency,
            'address' => $request->address,
            'about' => $request->about,
            'website' => $request->website,
            'twitter' => $request->twitter,
            'facebook' => $request->facebook,
            'linkedin' => $request->linkedin,
            'company_id' => $this->sessionDetails('company_id')
        ];

        if ($request->has('company_logo') && !is_null($request->company_logo)) {
            $data['company_logo'] = '/images/brands/logos/' . $this->uploadImage($request->company_logo, 'images/brands/logos');
        }
       
        if (!$companySetting) {
            CompanySetting::create($data);
        } else {
            $companySetting->update($data);
        }

        return response()->json(['message' => 'Company settings updated succesfully', 'data' => null], 200);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanySetting $companySetting)
    {
        //
    }
}
