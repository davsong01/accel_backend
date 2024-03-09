<?php

namespace App\Http\Controllers;

use App\Models\Commission;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class CommissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!empty($request->paginate) && $request->paginate == 'false') {
            $commissions = Commission::where('company_id', $this->sessionDetails('company_id'))->get();
        } else {
            $commissions = Commission::where('company_id', $this->sessionDetails('company_id'))->paginate(20)->withQueryString();
        }
        return response()->json(['message' => 'Success', 'data' => $commissions], 200);
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
        $validator = Validator::make($request->all(), [
            'name' => [
                'required', Rule::unique('commissions')->where(function ($query) {
                    $query->where('company_id', $this->sessionDetails('company_id'));
                })
            ],
            'percentage' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $commission = Commission::updateOrcreate(['name' => $request->name, 'company_id' => $this->sessionDetails('company_id')], [
            'percentage' => $request->percentage,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        LogService::admin(auth()->user()->id, NULL, 'Create a commission (' . $commission->id . ')', 'CREATE');
        return response()->json(['message' => 'Created successfully', 'data' => $commission], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Commission $commission)
    {
        return response()->json(['message' => 'Success', 'data' => $commission], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Commission $commission)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Commission $commission)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required', Rule::unique('commissions')->where(function ($query) use ($commission) {
                    $query->where('company_id', $this->sessionDetails('company_id'))->where('name', '!=', $commission->name);
                })
            ],
            'percentage' => 'required|numeric',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $commission->update([
            'name' => $request->name,
            'percentage' => $request->percentage,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        LogService::admin(auth()->user()->id, NULL, 'Update a commission (' . $commission->id . ')', 'UPDATE');
        return response()->json(['message' => 'Commission Updated Successfully', 'data' => $commission], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commission $commission)
    {

        if ($this->definedRelations('Commission', $commission) > 0) {
            return response()->json(['message' => 'Unable to delete, resource in use', 'data' => []], 412);
        }

        LogService::admin(auth()->user()->id, NULL, 'Delete a commission (' . $commission->id . ')', 'DELETE');
        $commission->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => []], 200);
    }
}
