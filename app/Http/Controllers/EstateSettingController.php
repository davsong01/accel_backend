<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EstateSettings;
use App\Services\LogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;


class EstateSettingController extends Controller
{
   
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "form_payment_for_land" => 'sometimes',
            "form_payment_for_building" => "sometimes",
            "plot_size_unit" => "sometimes",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $estateSettings = EstateSettings::where('company_id',sessionDetails('company_id'))->first();
       
        if ($request->has('form_payment_for_land') && !is_null($request->form_payment_for_land)) {
            $data['form_payment_for_land'] = $request->form_payment_for_land;
        }

        if ($request->has('form_payment_for_building') && !is_null($request->form_payment_for_building)) {
            $data['form_payment_for_building'] = $request->form_payment_for_building;
        }

        if ($request->has('plot_size_unit') && !is_null($request->plot_size_unit)) {
            $data['plot_size_unit'] = $request->plot_size_unit;
        }

        if ($request->has('special_request_status') && !is_null($request->special_request_status)) {
            $validator = Validator::make($request->all(), [
                "special_request_status" => 'required',
                "expiry_date" => "sometimes",
            ]);
          
            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->messages()->first(),
                    'data' => null,
                ], 412);
            }

            $c_setting = [
                'special_request_status' => $request->special_request_status,
                'expiry_date' => $request->expiry_date ?? null,
            ];

            $data['customer_settings'] = json_encode($c_setting); 
            
        }
       
        $data['company_id'] = sessionDetails('company_id');
        
        if(!$estateSettings){
            EstateSettings::create($data);
        }else{
            $estateSettings->update($data);
        }
        
        LogService::admin(auth()->user()->id, null, 'Update an estate setting form expiry (' . $estateSettings->id .')', 'UPDATE');
        return response()->json(['message' => 'Estate settings updated succesfully', 'data' => null], 200);
    }

    public function getEstateSettings(){
        $estateSettings = EstateSettings::where('company_id', sessionDetails('company_id'))->first();
        $other = $estateSettings->customer_settings ? json_decode($estateSettings->customer_settings) : (object) [];
        $estateSettings->special_request_status = $other->special_request_status ?? null;
        $estateSettings->expiry_date = $other->expiry_date ?? null;
        
        return response()->json(['message' => 'Retrieved succesfully', 'data' => $estateSettings], 200);
    }

}