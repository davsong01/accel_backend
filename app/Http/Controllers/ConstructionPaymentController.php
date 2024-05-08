<?php

namespace App\Http\Controllers;

use App\Models\ConstructionPayment;
use App\Models\ConstructionPaymentThread;
use App\Models\Customer;
use App\Models\Estate;
use App\Models\EstateConstructionSetting;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ConstructionPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = $this->sessionDetails('company_id');
        $list = ConstructionPayment::where('company_id', $company_id)->latest()->paginate();
        return response()->json(['message' => 'Success', 'data' => $list], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $company_id = $this->sessionDetails('company_id');

        $validator = Validator::make($request->all(), [
            'estate_construction_setting_ids' => ['required', 'array'],
            'estate_construction_setting_ids.*' => "exists:estate_construction_settings,id,company_id,$company_id",
            'customer_id' => ['required', "exists:customers,id,company_id,$company_id"],
            'chart_of_account_id' => ['required', "exists:chart_of_accounts,id,company_id,$company_id"],
            'contract_id' => ['required', "exists:customer_contracts,id,customer_id,$request->customer_id"],
            'amount_paid' => ['required', 'min:1', 'integer', 'lte:total_amount'],
            'payment_slip' => ['file', 'sometimes', 'mimetypes:image/*,application/pdf', 'max:512' ],
            'description' => ['sometimes', 'string'],
            'total_amount' => [
                'required',
                'min:1',
                'integer',
                function ($attribute, $value, $fail) use ($request) {
                    $totalPrice = 0;
                    foreach ($request->estate_construction_setting_ids as $productId) {
                        $product = EstateConstructionSetting::find($productId);
                        if (!$product) {
                            $fail('One or more products do not exist.');
                            return;
                        }
                        $totalPrice += $product->price;
                    }
                    $totalPrice = round($totalPrice);

                    if ($totalPrice != $value) {
                        $fail("The total amount must be equal to the sum of stager prices which is $totalPrice.");
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $user =  auth()->user();
        $customer = Customer::find($request->customer_id);
        $attributes = ['company_id' => $company_id, 'created_by' => auth()->user()->id];


        $data = $request->only((new ConstructionPayment)->getFillable());
        $data = array_merge($data, $attributes);
        
        $created = ConstructionPayment::create($data);

        if ($request->hasFile('payment_slip')) {
            $filepath = $this->uploadProtectedFile($attributes['payment_slip'], 'payment_slips')['filepath'];
            $attributes['payment_slip'] = $filepath;
        }

        $threads = $request->only((new ConstructionPaymentThread)->getFillable());
        $threads = array_merge(
            $threads,
            $attributes,
            [
                'balance_before' => $request->total_amount,
                'balance_after' => $request->total_amount - $request->amount_paid
            ]
        );

        $created->payments()->create($threads);

        LogService::admin($user->id, $customer->user->id, 'Create a construction payment (' . $created->id  . ')', 'CREATE');
        return response()->json(['message' => 'Created successfully', 'data' => $created], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(ConstructionPayment $payment)
    {
        return response()->json(['message' => 'Fetched successfully', 'data' => $payment->load('current_building_phase')], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConstructionPayment $payment)
    {
        $user = auth()->user();
        $customer = Customer::find($payment->customer_id);

        $balance = intval($payment->total_amount) - intval($payment->amount_paid);

        $validator = Validator::make($request->all(), [
            'chart_of_account_id' => ['required_with:amount_paid', 'exists:chart_of_accounts,id'],
            'amount_paid' => ['required_with:amount_paid', 'min:1', 'integer', "lte:$balance"],
            'payment_slip' => ['file', 'sometimes', 'mimetypes:image/*,application/pdf', 'max:512' ],
            'current_building_phase_id' => [
                'sometimes',
                Rule::in($payment->estate_construction_setting_ids)
            ]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $attributes = $validator->validated();

        if ($request->hasFile('payment_slip')) {
            $filepath = $this->uploadProtectedFile($attributes['payment_slip'], 'payment_slips')['filepath'];
            $attributes['payment_slip'] = $filepath;
        }

        $threads = array_merge(
            $attributes,
            [
                'balance_before' => $payment->total_amount - $payment->amount_paid,
                'balance_after' =>  $balance - $request->amount_paid
            ]
        );

        if ($request->has('current_building_phase_id')) {
            $payment->update(['current_building_phase_id' => $request->current_building_phase_id]);
        } else {
            $payment->payments()->create($threads);
            $payment->update(['amount_paid' => $payment->amount_paid + $request->amount_paid]);
        }

        LogService::admin($user->id, $customer->user->id, 'Updated a construction payment (' . $payment->id  . ')', 'UPDATE');
        return response()->json(['message' => 'Updated successfully', 'data' => $payment], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConstructionPayment $payment)
    {
        $user = auth()->user();
        $customer = Customer::find($payment->customer_id);

        LogService::admin($user->id, $customer->user->id, 'Delete a construction payment (' . $payment->id  . ')', 'DELETE');

        $payment->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }


    /**
     * Search a listing of resource.
     */
    public function search(Request $request)
    {
        $params = $request->only(['customer_status', 'all_customers', 'subscriber_status', 'completed_subscriber_status']);
        $company_id = $this->sessionDetails('company_id');

        $query = ConstructionPayment::where('company_id', $company_id)->where($params);

        if ($name = $request->input('name'))
            $query->where('name', 'LIKE', '%' . $name . '%');

        if ($request->input('created_by') && $name = $request->input('created_by')) {
            $users = User::with(['staff'])->where('company_id', $company_id)->whereNotNull('company_id')
                ->where('first_name', 'LIKE', '%' . $name . '%')
                ->orWhere('last_name', 'LIKE', '%' . $name . '%')
                ->orWhere('middle_name', 'LIKE', '%' . $name . '%')->get();

            $created_by_ids = $users->whereNotNull('staff.id')->pluck('staff.id')->toArray();
            $query->whereIn('created_by', $created_by_ids);
        }

        if ($request->input('customer') && $name = $request->input('customer')) {
            $users = User::with(['customer'])->where('company_id', $company_id)->whereNotNull('company_id')
                ->where('first_name', 'LIKE', '%' . $name . '%')
                ->orWhere('last_name', 'LIKE', '%' . $name . '%')
                ->orWhere('middle_name', 'LIKE', '%' . $name . '%')->get();

            $customer_ids = $users->whereNotNull('customer.id')->pluck('customer.id')->toArray();
            $query->whereIn('customer_id', $customer_ids);
        }

        if ($request->input('estates') && $name = $request->input('estates')) {
            $estates = Estate::where('company_id', $company_id)->whereNotNull('company_id')
                ->where('name', 'LIKE', '%' . $name . '%')
                ->get();

            $estates_ids = $estates->whereNotNull('id')->pluck('id')->toArray();
            $query->whereJsonContains('estate_ids', $estates_ids);
        }


        $recipients = $query->latest()->paginate(20);
        return response()->json(['message' => Success, 'data' => $recipients], 200);
    }
}
