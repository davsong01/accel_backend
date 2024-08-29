<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AccountCategory;
use App\Models\AccountType;
use App\Services\LogService;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class AccountCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $account_category = AccountCategory::where('company_id', sessionDetails('company_id'))->orderBy('created_at', 'desc');

        if (!empty($request->status)) {
            $account_category = $account_category->where('status', $request->status);
        }

        if (!empty($request->paginate) && $request->paginate == 'false') {
            $account_category = $account_category->get();
        } else {
            $account_category = $account_category->paginate(20)->withQueryString();
        }

        return response()->json(['message' => 'Success', 'data' => $account_category], 200);
    }

    public function modifyAccountCategory($id)
    {
        $account_cat = AccountCategory::where('company_id', sessionDetails('company_id'))->where('id', $id)->first();
        if ($account_cat) {
            if ($account_cat->status == 'active') {
                $account_cat->update(['status' => 'inactive']);
            } else {
                $account_cat->update(['status' => 'active']);
            }
            LogService::admin(auth()->user()->id, null, "Modify account category ($account_cat->id)", 'UPDATE');
        }
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
            'name' => [
                'required', Rule::unique('account_categories')->where(function ($query) {
                    $query->where('company_id', sessionDetails('company_id'));
                })
            ],
            'account_group_id' => 'required|exists:account_groups,id,company_id,' . sessionDetails('company_id'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => [],
            ], 412);
        }

        $account_group_id = AccountCategory::updateOrCreate(['name' => $request->name, 'company_id' => sessionDetails('company_id')], [
            'account_group_id' => $request->account_group_id,
        ]);

        LogService::admin(auth()->user()->id, null, 'Update or create an account category(' . $account_group_id->id . ')', 'CREATE');
        return response()->json(['message' => 'Created successfully', 'data' => $account_group_id], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(AccountCategory $accountCategory)
    {
        return response()->json(['message' => 'Success', 'data' => $accountCategory], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccountCategory $accountCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccountCategory $accountCategory)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required', Rule::unique('account_categories')->where(function ($query) use ($accountCategory) {
                    $query->where('company_id', sessionDetails('company_id'));
                    $query->where('name', '!=', $accountCategory->name);
                })
            ],
            'account_group_id' => 'required|exists:account_groups,id,company_id,' . sessionDetails('company_id'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $accountCategory->update([
            'name' => $request->name,
            'account_group_id' => $request->account_group_id,
            'company_id' => sessionDetails('company_id'),
        ]);

        LogService::admin(auth()->user()->id, null, 'Update an account category(' . $accountCategory->id . ')', 'UPDATE');
        return response()->json(['message' => 'Updated Succesfully', 'data' => $accountCategory], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountCategory $accountCategory)
    {
        if ($this->definedRelations('AccountCategory', $accountCategory) > 0) {
            return response()->json(['message' => 'Unable to delete, resource in use', 'data' => []], 412);
        }

        LogService::admin(auth()->user()->id, null, 'Delete an account category(' . $accountCategory->id . ')', 'DELETE');
        $accountCategory->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => []], 200);
    }
}
