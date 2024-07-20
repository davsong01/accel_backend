<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContractUnderConstructionRequest;
use App\Http\Requests\UpdateContractUnderConstructionRequest;
use App\Http\Requests\UpgradeContractUnderConstructionRequest;
use App\Models\ContractUnderConstruction;
use App\Models\Customer;
use App\Models\User;
use App\Services\LogService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContractUnderConstructionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = $this->sessionDetails('company_id');
        $list = ContractUnderConstruction::where('company_id', $company_id)->latest()->paginate();
        return response()->json(['message' => 'Success', 'data' => $list], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContractUnderConstructionRequest $request)
    {
        $company_id = $this->sessionDetails('company_id');

        $user =  auth()->user();
        $customer = Customer::find($request->customer_id);
        $attributes = ['company_id' => $company_id, 'created_by_id' => auth()->user()->id];


        $data = $request->validated();
        $data = array_merge($data, $attributes);
        
        $created = ContractUnderConstruction::create($data);

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
    public function show(ContractUnderConstruction $contract)
    {
        return response()->json(['message' => 'Fetched successfully', 'data' => $contract], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContractUnderConstructionRequest $request, ContractUnderConstruction $contract)
    {
        $user = auth()->user();
        $customer = Customer::find($contract->customer_id);
        $company_id = $this->sessionDetails('company_id');
        $balance = intval($contract->total_amount) - intval($contract->amount_paid);


        $attributes = array_merge($request->validated(), ['company_id' => $company_id, 'created_by_id' => auth()->user()->id]);

        if ($request->hasFile('payment_slip')) {
            $filepath = $this->uploadProtectedFile($attributes['payment_slip'], 'payment_slips')['filepath'];
            $attributes['payment_slip'] = $filepath;
        }

        $threads = array_merge(
            $attributes,
            [
                'balance_before' => $contract->total_amount - $contract->amount_paid,
                'balance_after' =>  $balance - $request->amount_paid,
                'estate_construction_setting_ids' => $contract->estate_construction_setting_ids
            ]
        );

        if(isset($attributes['amount_paid'])){
            $contract->payments()->create($threads);
            $contract->update(['amount_paid' => $contract->amount_paid + $request->amount_paid]);
            }else {
            $contract->update($attributes);  
        }
        

        LogService::admin($user->id, $customer->user->id, 'Updated a construction payment (' . $contract->id  . ')', 'UPDATE');
        return response()->json(['message' => 'Updated successfully', 'data' => $contract], 200);
    }

    /**
     * Upgrade a payment.
     */
    public function upgrade(UpgradeContractUnderConstructionRequest $request, ContractUnderConstruction $contract)
    {
        $user = auth()->user();
        $customer = Customer::find($contract->customer_id);
        $company_id = $this->sessionDetails('company_id');

        $balance_before = intval($request->total_amount) - intval($contract->amount_paid);
        $stagers = array_unique(array_merge($request->estate_construction_setting_ids ?? [], $contract->estate_construction_setting_ids ?? []));


        $attributes = $request->validated();
        $attributes['estate_construction_setting_ids'] = $stagers;
        
        if ($request->hasFile('payment_slip')) {
            $filepath = $this->uploadProtectedFile($attributes['payment_slip'], 'payment_slips')['filepath'];
            $attributes['payment_slip'] = $filepath;
        }

        if(isset($attributes['amount_paid']))
            $attributes['amount_paid'] = $contract->amount_paid + $request->amount_paid; // sum old amount + new amount paid
        $contract->update($attributes);

        $threads = [
            'balance_before' => $balance_before,
            'balance_after' =>  $balance_before - $request->amount_paid,
            'company_id' => $company_id, 'created_by_id' => auth()->user()->id
        ];
        $threads = array_merge($attributes, $threads);

        if(isset($threads['amount_paid'])){
            $threads['amount_paid'] = $request->amount_paid; // ensure save the amount actually paid
            $contract->payments()->create($threads);
        }
    
        LogService::admin($user->id, $customer->user->id, 'Upgrade a construction payment (' . $contract->id  . ')', 'UPDATE');
        return response()->json(['message' => 'Upgrade successfully', 'data' => $contract], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContractUnderConstruction $contract)
    {
        $user = auth()->user();
        $customer = Customer::find($contract->customer_id);

        LogService::admin($user->id, $customer->user->id, 'Delete a construction payment (' . $contract->id  . ')', 'DELETE');

        $contract->payments()->delete();
        $contract->delete();
        return response()->json(['message' => 'Deleted Successfully.', 'data' => null], 200);
    }


    /**
     * Search a listing of resource.
     */
    public function search(Request $request)
    {
        $params = $request->only(['company_id', 'customer_id', 'contract_id', 'created_by', 'amount_paid', 'total_amount', 'current_building_phase_id', 'chart_of_account_id']);
        $company_id = $this->sessionDetails('company_id');
    
        if(isset($params['created_by'])){
            $params['created_by_id'] = $params['created_by'];
            unset($params['created_by']);
        }

        $query = ContractUnderConstruction::where('company_id', $company_id)->where($params);

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
