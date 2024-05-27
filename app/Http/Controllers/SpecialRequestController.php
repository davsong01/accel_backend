<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\SpecialRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SpecialRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $special_requests = SpecialRequest::where('company_id', $this->sessionDetails('company_id'))
        ->latest()->paginate(20)->withQueryString();

        return response()->json(['message' => 'Success', 'data' => $special_requests], 200);
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
    public function show(SpecialRequest $specialRequest): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SpecialRequest $specialRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SpecialRequest $specialRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SpecialRequest $specialRequest)
    {
        //
    }

    /**
     * Search a listing of resource.
     */
    public function search(Request $request)
    {
        $$params = $request->only(['company_id', 'branch_id', 'customer_id', 'contract_id', 'original_owner_id', 'new_owner_id']);
        $company_id = $this->sessionDetails('company_id');

        $query = SpecialRequest::where('company_id', $company_id)->where($params);

        if ($type = $request->input('type')) {
            $query->where('type', 'LIKE', '%' . $type . '%');
        }

        if ($current_contract = $request->input('current_contract')) {
            $query->where('current_contract', 'LIKE', '%' . $current_contract . '%');
        }

        if ($new_contract = $request->input('new_contract')) {
            $query->where('new_contract', 'LIKE', '%' . $new_contract . '%');
        }

        if ($name = $request->input('branch_name')) {
            $branches = Branch::where('company_id', $company_id)->whereNotNull('company_id')
                ->where('name', 'LIKE', '%' . $name . '%')
                ->get();

            $branch_ids = $branches->whereNotNull('id')->pluck('id')->toArray();
            $query->whereIn('special_requestable.branch_id', $branch_ids);
        }

        $special_requests = $query->latest()->paginate(20);
        return response()->json(['message' => 'success', 'data' => $special_requests], 200);
    }
}
