<?php

namespace App\Http\Controllers;

use App\Models\PaymentMode;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaymentModeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = $this->sessionDetails('company_id');
        $list = PaymentMode::where('company_id', $company_id)->latest()->paginate();
        return response()->json(['message' => 'Success', 'data' => $list], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $company_id = 1;

        $validator = Validator::make($request->all(), [
            'mode' => [
                'required',
                'string',
                Rule::unique('payment_modes')->where(fn($query) => $query->where('company_id', $company_id)),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $user =  auth()->user();
        $attributes = [...$validator->validated(), 'company_id' => $company_id];
        
        $created = PaymentMode::create($attributes);

        LogService::admin($user->id, null, 'Create a payment mode (' . $created->id  . ')', 'CREATE', $company_id);
        return response()->json(['message' => 'Created successfully', 'data' => $created], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentMode $mode)
    {
        return response()->json(['message' => 'Fetched successfully', 'data' => $mode], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentMode $mode)
    {
        $company_id = $this->sessionDetails('company_id');
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'mode' => [
                'required',
                'string',
                Rule::unique('payment_modes')->where(fn($query) => $query->where('company_id', $company_id)),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $attributes = $validator->validated();

        $mode->update($attributes);

        LogService::admin($user->id, null, 'Updated a payment mode (' . $mode->id  . ')', 'UPDATE');
        return response()->json(['message' => 'Updated successfully', 'data' => $mode], 200);
    }

    /**
     * Search a listing of resource.
     */
    public function search(Request $request)
    {

        $company_id = $this->sessionDetails('company_id');

        $query = PaymentMode::where('company_id', $company_id);

        if ($mode = $request->input('mode')){
            $query->where('mode', 'LIKE', '%' . $mode . '%');
        }

        $modes = $query->latest()->paginate(20);
        return response()->json(['message' => 'Success', 'data' => $modes], 200);
    }
}
