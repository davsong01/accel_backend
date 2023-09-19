<?php

namespace App\Http\Controllers;

use App\Models\FormPayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FormPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(FormPayment $formPayment): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FormPayment $formPayment): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FormPayment $formPayment): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FormPayment $formPayment): RedirectResponse
    {
        //
    }
}
