<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class AccountTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $account_type = AccountType::with('account_category')->where('company_id', sessionDetails('company_id'))->orderBy('created_at', 'desc');

        if (!empty($request->status)) {
            $account_type = $account_type->where('status', $request->status);
        }

        $account_type = $account_type->get();

        return response()->json(['message' => 'Success', 'data' => $account_type], 200);
    }

    public function modifyAccountType($id)
    {
        $account_type = AccountType::where('company_id', sessionDetails('company_id'))->where('id', $id)->first();
        if ($account_type) {
            if ($account_type->status == 'active') {
                $account_type->update(['status' => 'inactive']);
            } else {
                $account_type->update(['status' => 'active']);
            }
        }

        LogService::admin(auth()->user()->id, null, 'Modify an account type(' . $account_type->id . ')', 'UPDATE');
        return response()->json(['message' => 'Successfully updated', 'data' => []], 200);
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
            'type' => 'required',
            'account_category_id' => 'required|exists:account_categories,id,company_id,' . sessionDetails('company_id'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $account_type = AccountType::updateOrcreate([
            'type' => $request->type,
            'account_category_id' => $request->account_category_id,
            'company_id' => sessionDetails('company_id'),
        ]);

        LogService::admin(auth()->user()->id, null, 'Create an account type(' . $account_type->id . ')', 'CREATE');
        return response()->json(['message' => 'Created successfully', 'data' => $account_type], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(AccountType $account_type)
    {
        if ($account_type) {
            return response()->json(['message' => 'Retrieved successfully', 'data' => $account_type], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => $account_type], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccountType $account_type): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccountType $account_type)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'account_category_id' => 'required|exists:account_categories,id,company_id,' . sessionDetails('company_id'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $account_type->update([
            'type' => $request->type,
            'account_category_id' => $request->account_category_id
        ]);

        LogService::admin(auth()->user()->id, null, 'Update an account type(' . $account_type->id . ')', 'UPDATE');
        return response()->json(['message' => 'Updated Successfully.', 'data' => $account_type], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountType $account_type)
    {
        // $count = EstatePropertyTypeDetails::where(['plot_size' => $plotsize->size, 'company_id' => $plotsize->company_id])->count();
        // if ($count > 0) {
        //     return response()->json(['message' => 'Unable to delete, resource in use', 'data' => null], 412);
        // }
        if ($account_type->account_category || $account_type->chartofaccount->count() || $account_type->status != 'inactive') {
            return response()->json([
                'error' => 'Ops, looks like the account type is in use',
                'data' => null,
            ], 409);
        }

        LogService::admin(auth()->user()->id, null, 'Delete an account type(' . $account_type->id . ')', 'DELETE');

        $account_type->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }
}
