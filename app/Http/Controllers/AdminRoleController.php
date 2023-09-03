<?php

namespace App\Http\Controllers;

use App\Models\StaffRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminRoleController extends Controller
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
    public function show(StaffRole $StaffRole): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StaffRole $StaffRole): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StaffRole $StaffRole): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StaffRole $StaffRole): RedirectResponse
    {
        //
    }
}
