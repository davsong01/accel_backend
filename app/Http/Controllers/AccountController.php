<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coa = Account::where('company_id', sessionDetails('company_id'))->orderBy('created_at', 'desc')->get();
        return response()->json(['message' => 'Success', 'data' => $coa], 200);
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
            'accounttype_id' => 'required|exists:account_types,id,company_id,' . sessionDetails('company_id'),
            'accountclassification_id' => 'required|exists:account_classifications,id,company_id,' . sessionDetails('company_id'),
            'name' => [
                'required', Rule::unique('accounts')->where(function ($query) {
                    $query->where('company_id', sessionDetails('company_id'));
                })
            ],
            'number' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $account = Account::updateOrcreate([
            'accounttype_id' => $request->accounttype_id,
            'accountclassification_id' => $request->accountclassification_id,
            'name' => $request->name,
            'number' => $request->number,
            'status' => 1,
            'company_id' => sessionDetails('company_id'),
        ]);

        LogService::admin(auth()->user()->id, null, 'Create or update an account(' . $account->id . ')', 'CREATE');
        return response()->json(['message' => 'Created successfully', 'data' => $account], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        if ($account) {
            return response()->json(['message' => 'Retrieved successfully', 'data' => $account], 200);
        } else {
            return response()->json(['message' => 'No records found', 'data' => $account], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Account $account)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        $validator = Validator::make($request->all(), [
            'accounttype_id' => 'required|exists:account_types,id,company_id,' . sessionDetails('company_id'),
            'accountclassification_id' => 'required|exists:account_classifications,id,company_id,' . sessionDetails('company_id'),
            'name' => 'required',
            'number' => 'required',
            'status' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $account->update([
            'accounttype_id' => $request->accounttype_id,
            'accountclassification_id' => $request->accountclassification_id,
            'name' => $request->name,
            'number' => $request->number,
            'status' => $request->status ?? $account->status,
            'company_id' => sessionDetails('company_id'),
        ]);

        LogService::admin(auth()->user()->id, null, 'Update an account(' . $account->id . ')', 'UPDATE');
        return response()->json(['message' => 'Created successfully', 'data' => $account], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        LogService::admin(auth()->user()->id, null, 'Delete an account(' . $account->id . ')', 'DELETE');

        $account->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }
}
