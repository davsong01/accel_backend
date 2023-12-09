<?php

namespace App\Http\Controllers;

use App\Models\Lga;
use App\Models\Pfa;
use App\Models\Bank;
use App\Models\States;
use App\Models\Country;
use App\Models\Currency;
use Illuminate\Http\Request;
use App\Services\GeneralResourceService;
use NunoMaduro\Collision\Adapters\Phpunit\State;

class GeneralController extends Controller
{
    //
    public function getCountries(){
        $countries = Country::all();
        
        return response()->json(['message' => 'success', 'data' => $countries], 200);
    }

    public function getStates(Request $request)
    {
        $states = States::orderBy('name');
        
        if(!empty($request->country_id)){
            $states = $states->where('country_id', $request->country_id);
        }

        $states = $states->get();

        return response()->json(['message' => 'success', 'data' => $states], 200);
    }

    public function getLgas(Request $request)
    {
        $lgas = Lga::orderBy('name');

        if (!empty($request->state_id)) {
            $lgas = $lgas->where('state_id', $request->state_id);
        }
        $lgas = $lgas->get();

        return response()->json(['message' => 'success', 'data' => $lgas], 200);
    }

    public function getBanks($country_id = null){
        $banks = Bank::orderBy('id');

        if (!empty($country_id)) {
            $banks = $banks->where('country_id', $country_id);
        }

        $banks = $banks->get();

        return response()->json(['message' => 'success', 'data' => $banks], 200);
    }

    public function getCurrencies()
    {
        $currencies = Currency::all();

        return response()->json(['message' => 'success', 'data' => $currencies], 200);
    }

    public function getPfas($country_id = null)
    {
        $pfas = Pfa::orderBy('id');
        if(!empty($country_id)){
            $pfas = $pfas->where('country_id', $country_id);
        }
        $pfas = $pfas->get();
        return response()->json(['message' => 'success', 'data' => $pfas], 200);
    }

}
