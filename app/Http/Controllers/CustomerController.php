<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Models\CustomerContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $customers = Customer::with(['user:id,first_name,last_name,status', 'contracts:id,status,customer_id'])->withCount('contracts')
            ->where('company_id', $this->sessionDetails('company_id'))
            ->where('branch_id', auth()->user()->staff->branch_id)->paginate(20);

        foreach ($customers as $customer) {
            $statuses = $customer->contracts->pluck('status')->toArray();
            $customer->contract_statuses = array_count_values($statuses);
        }

        return response()->json(['message' => 'success', 'data' => $customers], 200);
    }

    public function CustomerCount()
    {
        $all = CustomerContract::where('company_id', $this->sessionDetails('company_id'))->count();
        $leads = CustomerContract::where('company_id', $this->sessionDetails('company_id'))->where('status', 'lead')->count();
        $subscriber = CustomerContract::where('company_id', $this->sessionDetails('company_id'))->where('status', 'subscriber')->count();
        $prospect = CustomerContract::where('company_id', $this->sessionDetails('company_id'))->where('status', 'prospect')->count();

        $contract = [
            'all' => $all,
            'lead' => $leads,
            'subscriber' => $subscriber,
            'prospect' => $prospect,
        ];

        return response()->json(['message' => 'success', 'data' => $contract], 200);
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
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required|in:male,female',
            'email' => [
                'required', 'email', Rule::unique('users')->where(function ($query) {
                    $query->where('company_id', $this->sessionDetails('company_id'));
                })
            ],
            'phone_number' => 'required',
            // 'city' => 'required|exists:cities,id,company_id,' . $this->sessionDetails('company_id'),
            // 'location' => 'required|exists:locations,id,company_id,' . $this->sessionDetails('company_id'),
            // 'estate' => 'required|exists:estates,id,company_id,' . $this->sessionDetails('company_id'),
            'residential_address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        // Add User
        $user = User::updateOrCreate([
            'first_name' => $request->first_name,
            'email' => $request->email,
            'gender' => $request->gender,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'residential_address' => $request->residential_address,
            'company_id' => $this->sessionDetails('company_id'),
            'branch_id' => $this->sessionDetails('branch_id'),
            'email_verified_at' => now(),
            'status' => 'active',
            'type' => 'customer',
            'company_id' => $this->sessionDetails('company_id'),
            'branch_id' => $this->sessionDetails('branch_id'),
        ]);

        if ($user) {
            // Add Customer
            $customer = Customer::updateOrCreate([
                'company_id' => $this->sessionDetails('company_id'),
                'branch_id' => $this->sessionDetails('branch_id'),
                'user_id' => $user->id,
                'verified' => 1,
            ]);
        }

        return response()->json(['message' => 'success', 'data' => $user], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([

            'name' => 'required',

            'email' => [

                'required', 'email', Rule::unique('transporters')->where(function ($query) {

                    $query->where('deleted', '=', '0')

                        ->where('id', '!=', $id);
                })

            ],

        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
