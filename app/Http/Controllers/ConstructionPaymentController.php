<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpgradeConstructionPaymentRequest;
use App\Models\ConstructionPayment;
use App\Models\ConstructionPaymentThread;
use App\Models\Customer;
use App\Models\Estate;
use App\Models\EstateConstructionSetting;
use App\Models\User;
use App\Services\LogService;
use Carbon\Carbon;
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
            'contract_id' => ['required', "exists:customer_contracts,id,deleted_at,NULL,customer_id,$request->customer_id"],
            'amount_paid' => ['required', 'min:1', 'integer', 'lte:total_amount'],
            'payment_slip' => ['file', 'sometimes', 'mimetypes:image/*,application/pdf', 'max:512' ],
            'current_building_phase_id' => [
                'sometimes',
                Rule::in($request->estate_construction_setting_ids)
            ],
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
        $attributes = ['company_id' => $company_id, 'created_by_id' => auth()->user()->id];


        $data = $validator->validated();
        $data = array_merge($data, $attributes);
        
        $created = ConstructionPayment::create($data);

        if ($request->hasFile('payment_slip')) {
            $filepath = $this->uploadProtectedFile($attributes['payment_slip'], 'payment_slips')['filepath'];
            $attributes['payment_slip'] = $filepath;
        }

        $threads = array_merge($data,
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
        return response()->json(['message' => 'Fetched successfully', 'data' => $payment], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConstructionPayment $payment)
    {
        $user = auth()->user();
        $customer = Customer::find($payment->customer_id);
        $company_id = $this->sessionDetails('company_id');
        $balance = intval($payment->total_amount) - intval($payment->amount_paid);

        $validator = Validator::make($request->all(), [
            'chart_of_account_id' => ['required_with:amount_paid', "exists:chart_of_accounts,id,company_id,$company_id"],
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

        $attributes = array_merge($validator->validated(), ['company_id' => $company_id, 'created_by_id' => auth()->user()->id]);

        if ($request->hasFile('payment_slip')) {
            $filepath = $this->uploadProtectedFile($attributes['payment_slip'], 'payment_slips')['filepath'];
            $attributes['payment_slip'] = $filepath;
        }

        $threads = array_merge(
            $attributes,
            [
                'balance_before' => $payment->total_amount - $payment->amount_paid,
                'balance_after' =>  $balance - $request->amount_paid,
                'estate_construction_setting_ids' => $payment->estate_construction_setting_ids
            ]
        );

        if(isset($attributes['amount_paid'])){
            $payment->payments()->create($threads);
            $payment->update(['amount_paid' => $payment->amount_paid + $request->amount_paid]);
            }else {
            $payment->update($attributes);  
        }
        

        LogService::admin($user->id, $customer->user->id, 'Updated a construction payment (' . $payment->id  . ')', 'UPDATE');
        return response()->json(['message' => 'Updated successfully', 'data' => $payment], 200);
    }

    /**
     * Upgrade a payment.
     */
    public function upgrade(UpgradeConstructionPaymentRequest $request, ConstructionPayment $payment)
    {
        $user = auth()->user();
        $customer = Customer::find($payment->customer_id);
        $company_id = $this->sessionDetails('company_id');

        $balance_before = intval($request->total_amount) - intval($payment->amount_paid);
        $stagers = array_unique(array_merge($request->estate_construction_setting_ids ?? [], $payment->estate_construction_setting_ids ?? []));


        $attributes = $request->validated();
        $attributes['estate_construction_setting_ids'] = $stagers;
        
        if ($request->hasFile('payment_slip')) {
            $filepath = $this->uploadProtectedFile($attributes['payment_slip'], 'payment_slips')['filepath'];
            $attributes['payment_slip'] = $filepath;
        }

        if(isset($attributes['amount_paid']))
            $attributes['amount_paid'] = $payment->amount_paid + $request->amount_paid; // sum old amount + new amount paid
        $payment->update($attributes);

        $threads = [
            'balance_before' => $balance_before,
            'balance_after' =>  $balance_before - $request->amount_paid,
            'company_id' => $company_id, 'created_by_id' => auth()->user()->id
        ];
        $threads = array_merge($attributes, $threads);

        if(isset($threads['amount_paid'])){
            $threads['amount_paid'] = $request->amount_paid; // ensure save the amount actually paid
            $payment->payments()->create($threads);
        }
    
        LogService::admin($user->id, $customer->user->id, 'Upgrade a construction payment (' . $payment->id  . ')', 'UPDATE');
        return response()->json(['message' => 'Upgrade successfully', 'data' => $payment], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConstructionPayment $payment)
    {
        $user = auth()->user();
        $customer = Customer::find($payment->customer_id);

        LogService::admin($user->id, $customer->user->id, 'Delete a construction payment (' . $payment->id  . ')', 'DELETE');

        $payment->payments()->delete();
        $payment->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }


    /**
     * Search a listing of resource.
     */
    public function search(Request $request)
    {
        $params = $request->only(['company_id', 'customer_id', 'contract_id', 'created_by', 'amount_paid', 'total_amount', 'current_building_phase_id', 'chart_of_account_id']);
        $company_id = $this->sessionDetails('company_id');
    
        if($params['created_by']){
            $params['created_by_id'] = $params['created_by'];
            unset($params['created_by']);
        }

        $query = ConstructionPayment::where('company_id', $company_id)->where($params);

        $from = $request->input('from');
        $to = $request->input('to');
        if ($from && $to) {
            $from = Carbon::parse($request->input('from'));

            $to = Carbon::parse($request->input('to'));
            if ($to->format('H:i:s') == '00:00:00')
                $to->endOfDay();

            $query->whereBetween('created_at', [$from, $to]);
        }

        if ($request->input('name') && $name = $request->input('name')) {
            $users = User::with(['staff', 'customer'])
                ->where('company_id', $company_id)
                ->whereNotNull('company_id')
                ->where(function ($query) use ($name) {
                    $query->where('first_name', 'LIKE', '%' . $name . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $name . '%')
                        ->orWhere('middle_name', 'LIKE', '%' . $name . '%');
                })
                ->get();
        
            $staff_ids = $users->pluck('staff.id')->filter()->toArray();
            $customer_ids = $users->pluck('customer.id')->filter()->toArray();
        
            $query->whereIn('created_by_id', $staff_ids)->orWhereIn('customer_id', $customer_ids);
        }
        

        if ($request->input('estate_construction_setting_ids') && $setting_ids = $request->input('estate_construction_setting_ids')) {
            $query->whereJsonContains('estate_construction_setting_ids', $setting_ids);
        }


        $recipients = $query->latest()->paginate(20);
        return response()->json(['message' => 'Success', 'data' => $recipients], 200);
    }
}
