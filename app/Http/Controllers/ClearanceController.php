<?php

namespace App\Http\Controllers;

use App\Models\Clearance;
use App\Models\ClearanceThread;
use App\Models\Customer;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClearanceController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_id = $this->sessionDetails('company_id');
        $list = Clearance::where('company_id', $company_id)->latest()->paginate();
        return response()->json(['message' => 'Success', 'data' => $list], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $company_id = $this->sessionDetails('company_id');

        $validator = Validator::make($request->all(), [
            'customer_id' => ['required', "exists:customers,id,company_id,$company_id"],
            'customer_contract_id' => ['required', "exists:customer_contracts,id,company_id,$company_id"],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $user =  auth()->user();
        $customer = Customer::find($request->customer_id);
        $attributes = ['company_id' => $company_id, ...$validator->validated()];
        
        $created = Clearance::create($attributes);

        LogService::admin($user->id, $customer->user->id, 'Created a clearance (' . $created->id  . ')', 'CREATE');
        return response()->json(['message' => 'Created successfully', 'data' => $created], 200);
    }


    /**
     * Display the specified resource.
     */
    public function show(Clearance $clearance)
    {
        $markdown = new \Illuminate\Mail\Markdown(view(), config('mail.markdown'));

        return $markdown->render('emails.certificate_of_clearance', ['name' => 'John Doe']);
        // return view('emails.certificate_of_clearance', ['name' => 'John Doe']);
        // return response()->json(['message' => 'Fetched successfully', 'data' => $clearance], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clearance $clearance)
    {
        $company_id = $this->sessionDetails('company_id');

        $user = auth()->user();
        $customer = Customer::find($clearance->customer_id);

        $validator = Validator::make($request->all(), [
            'comment' => ['required', 'min:3', 'string'],
            'status' => ['required', 'in:,APPROVE_PAYMENT_APPROVAL,APPROVE_CONSTRUCTION_APPROVAL,DECLINED'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()->first(),
                'data' => null,
            ], 412);
        }

        $attributes = $validator->validated();
        $data = ['certificate' => null];

        $clearance->comments()->create(['comment' => $attributes['comment'], 'company_id' => $company_id]);

        switch ($attributes['status']) {
            case 'APPROVE_CONSTRUCTION_APPROVAL':
                $data['status'] = 'APPROVED';
                // $data['certificate'] = generate
            break;
            case 'APPROVE_PAYMENT_APPROVAL':
                $data['status'] = 'AWAITING_CONSTRUCTION_APPROVAL';
            break;
            default:
                $data['status'] = $attributes['status'];
            break;
        }

        $clearance->update($data);

        LogService::admin($user->id, $customer->user->id, 'Updated a clearance (' . $clearance->id  . ')', 'UPDATE');
        return response()->json(['message' => 'Updated successfully', 'data' => $clearance], 200);
    }

}
