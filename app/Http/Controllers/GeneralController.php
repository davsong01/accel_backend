<?php

namespace App\Http\Controllers;

use App\Models\Lga;
use App\Models\States;
use App\Models\Country;
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

    public function getBanks(){
        $banks = GeneralResourceService::getBanks();

        return response()->json(['message' => 'success', 'data' => $banks], 200);
    }

    public function getCurrencies()
    {
        $currencies = GeneralResourceService::getCurrencies();

        return response()->json(['message' => 'success', 'data' => $currencies], 200);
    }

}
