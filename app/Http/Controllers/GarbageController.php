<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\CustomerForm;
use Illuminate\Http\Request;
use App\Models\ContractPayment;
use App\Models\CustomerContract;
use App\Models\CustomerProperty;
use App\Models\SeInfrastructure;
use App\Models\ContractPaymentThread;
use App\Models\SeInfrastructureThread;

class GarbageController extends Controller
{
    public function clearCustomerData(Request $request){
        if ($request->customer_id) {
            $customer = Customer::where('id', $request->customer_id)->first();

            if (!$customer) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'No customer with id: ' . $request->customer_id
                ]);
            }

            // Delete user
            $user = User::where('id', $customer->user_id)->delete();
            CustomerProperty::where('customer_id', $customer->id)->delete();
            CustomerContract::where('customer_id', $customer->id)->delete();
            Transaction::where('customer_id', $customer->id)->delete();
            CustomerForm::where('customer_id', $customer->id)->delete();
            ContractPayment::where('customer_id', $customer->id)->delete();
            ContractPaymentThread::where('customer_id', $customer->id)->delete();
            SeInfrastructure::where('customer_id', $customer->id)->delete();
            SeInfrastructureThread::where('customer_id', $customer->id)->delete();
            // !empty($customer->user) && $customer->user->count() > 0 ? $customer->user->delete() : '';
            // !empty($customer->properties) && $customer->properties->count() > 0 ? $customer->properties()->delete() : '';
            // !empty($customer->contracts) && $customer->contracts->count() > 0 ? $customer->contracts()->delete() : '';
            // // !empty($customer->generatedLegalDocuments) && $customer->generatedLegalDocuments->count() > 0 ? $customer->generatedLegalDocuments()->delete() : '';
            // !empty($customer->payments) && $customer->payments->count() > 0 ? $customer->payments()->delete() : '';
            // !empty($customer->transactions) && $customer->transactions->count() > 0 ? $customer->transactions()->delete() : '';
            // !empty($customer->response_form) && $customer->response_form->count() > 0 ? $customer->response_form()->delete() : '';

            $customer->delete();

            return response()->json([
                'status' => 'success',
            ]);
        }

    }
}
