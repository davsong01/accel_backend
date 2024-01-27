<?php

namespace App\Http\Controllers;

use App\Models\AccountGroup;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class AccountGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $account_group = AccountGroup::where('company_id', $this->sessionDetails('company_id'))->orderBy('created_at', 'desc');

        if (!empty($request->status)) {
            $account_group = $account_group->where('status', $request->status);
        }

        if (!empty($request->paginate) && $request->paginate == 'false') {
            $account_group = AccountGroup::where('company_id', $this->sessionDetails('company_id'))->get();
        } else {
            $account_group = AccountGroup::where('company_id', $this->sessionDetails('company_id'))->paginate(20)->withQueryString();
        }

        return response()->json(['message' => 'Success', 'data' => $account_group], 200);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function modifyAccountGroup($id)
    {
        $account_group = AccountGroup::where('company_id', $this->sessionDetails('company_id'))->where('id', $id)->first();
        if ($account_group) {
            if ($account_group->status == 'active') {
                $account_group->update(['status' => 'inactive']);
            } else {
                $account_group->update(['status' => 'active']);
            }
        }

        LogService::admin(auth()->user()->id, null, 'Modify account group(' . $account_group->id . ')', 'UPDATE');
        return response()->json(['message' => 'Successfully updated', 'data' => []], 200);
    }

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
                'required', Rule::unique('account_groups')->where(function ($query) {
                    $query->where('company_id', $this->sessionDetails('company_id'));
                })
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $account_group = AccountGroup::updateOrCreate(['name' => $request->name, 'company_id' => $this->sessionDetails('company_id')], []);

        LogService::admin(auth()->user()->id, null, 'Create or update an account group(' . $account_group->id . ')', 'CREATE');
        return response()->json(['message' => 'Created successfully', 'data' => $account_group], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(AccountGroup $accountGroup)
    {
        return response()->json(['message' => 'Success', 'data' => $accountGroup], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccountGroup $accountGroup)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccountGroup $accountGroup)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required', Rule::unique('account_categories')->where(function ($query) use ($accountGroup) {
                    $query->where('company_id', $this->sessionDetails('company_id'));
                    $query->where('name', '!=', $accountGroup->name);
                })
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $accountGroup->update([
            'name' => $request->name,
            'company_id' => $this->sessionDetails('company_id'),
        ]);

        LogService::admin(auth()->user()->id, null, 'Update an account group(' . $accountGroup->id . ')', 'UPDATE');
        return response()->json(['message' => 'Account Group Updated Successfully', 'data' => $accountGroup], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountGroup $accountGroup)
    {
        if ($this->definedRelations('AccountGroup', $accountGroup) > 0) {
            return response()->json(['message' => 'Unable to delete, resource in use', 'data' => []], 412);
        }

        LogService::admin(auth()->user()->id, null, 'Delete an account group(' . $accountGroup->id . ')', 'DELETE');

        $accountGroup->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => []], 200);
    }
}
