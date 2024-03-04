<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HCMController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = $this->sessionDetails('company_id');

        $totals = User::Where('company_id', $company_id)
            ->selectRaw('COUNT(*) as users, SUM(type = "staff") as staffs, SUM(type = "customer") as customers')->first();

        $anniversary = Staff::where('company_id', $company_id)
            ->with(['user'])
            ->whereBetween('date_of_employment', [now()->startOfDay(), now()->addMonth(3)->endOfDay()])
            ->get();

        return response()->json(['message' => 'success', 'data' => [
            'total' => $totals,
            'anniversary' => $anniversary,

        ]], 200);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
