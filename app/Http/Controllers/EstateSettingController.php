<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EstateSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;


class EstateSettingController extends Controller
{
   
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "form_payment_for_land" => 'sometimes',
            "form_payment_for_building" => "sometimes",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $estateSettings = EstateSettings::where('company_id',$this->sessionDetails('company_id'))->first();
       
        if ($request->has('form_payment_for_land') && !is_null($request->form_payment_for_land)) {
            $data['form_payment_for_land'] = $request->form_payment_for_land;
        }

        if ($request->has('form_payment_for_building') && !is_null($request->form_payment_for_building)) {
            $data['form_payment_for_building'] = $request->form_payment_for_building;
        }

        $data['company_id'] = $this->sessionDetails('company_id');
        
        if(!$estateSettings){
            EstateSettings::create($data);
        }else{
            $estateSettings->update($data);
        }
        
        return response()->json(['message' => 'Estate settings updated succesfully', 'data' => null], 200);
    }

}
