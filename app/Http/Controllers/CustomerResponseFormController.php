<?php

namespace App\Http\Controllers;

use App\Models\CustomerResponseForm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerResponseFormController extends Controller
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
    public function show(CustomerResponseForm $customerResponseForm)
    {
        //
    }

    public function declineResponseForm(){
        
    }

    public function acceptResponseForm()
    {
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerResponseForm $customerResponseForm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomerResponseForm $customerResponseForm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerResponseForm $customerResponseForm)
    {
        //
    }
}
