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

    public function getStates()
    {
        $states = States::all();

        return response()->json(['message' => 'success', 'data' => $states], 200);
    }

    public function getLgas()
    {
        $lgas = Lga::all();

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
