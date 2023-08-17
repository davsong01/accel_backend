<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $customers = Customer::with(['user:id,first_name,last_name,status'])->withCount('properties')
        ->where('company_id', $this->sessionDetails('company_id'))
        ->where('branch_id', auth()->user()->admin->branch_id)->paginate(20);

        if($request->has('count')){
            if($request->count == 'all'){
                $customers = $customers->count();
            }

            if ($request->count == 'lead') {
                $customers = $customers->where('type', 'lead')->count();
            }

            if ($request->count == 'subscriber') {
                $customers = $customers->where('type', 'subscriber')->count();
            }

            if ($request->count == 'prospect') {
                $customers = $customers->where('type', 'prospect')->count();
            }

            return response()->json(['message' => 'success', 'data' => $customers], 200);
        }
        
        return response()->json(['message'=>'success','data' => $customers], 200);
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
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
