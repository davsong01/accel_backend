<?php

namespace App\Http\Requests;

use App\Models\ConstructionPayment;
use App\Models\EstateConstructionSetting;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpgradeConstructionPaymentRequest extends FormRequest
{
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // we could use the roles here 
    }

    protected function prepareForValidation()
    {
        $this->merge(['company_id' => session()->get('company_id', null)]);
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);

        // Merge the payment data into the request data
        $payment = $this->route('payment');
        if ($payment instanceof ConstructionPayment) {
            $data = array_merge($data, ['payment' => $payment]);
        }

        return $data;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $company_id = $this->input('company_id');
        $payment = $this->payment;
        $balance_before = (floatval($this->input('total_amount')) - floatval($payment->amount_paid));
        $stagers = array_unique(array_merge($this->input('estate_construction_setting_ids') ?? [], $payment->estate_construction_setting_ids ?? []));

        return[
            'total_amount' => [
                'required',
                "min:$payment->total_amount",
                'numeric',
                function ($attribute, $value, $fail) use ($stagers) {
                    $totalPrice = 0;
                    $stagers = EstateConstructionSetting::whereIn('id', $stagers)->get();

                    foreach ($stagers as $product) {
                        if (!$product) {
                            $fail('One or more products do not exist.');
                            return;
                        }
                        $totalPrice += $product->price;
                    }
                    $totalPrice;

                    if ($totalPrice != $value) {
                        $fail("The total amount must be equal to the sum of stager prices which is $totalPrice.");
                    }
                },
            ],

            'estate_construction_setting_ids' => ['required', 'array'],

            'estate_construction_setting_ids.*' => "exists:estate_construction_settings,id,company_id,$company_id",

            'chart_of_account_id' => ['sometimes', "exists:chart_of_accounts,id,company_id,$company_id"],

            'amount_paid' => ['required_with:chart_of_account_id', 'min:1', 'numeric', "lte:" . abs($balance_before)],

            'payment_slip' => ['file', 'sometimes', 'mimetypes:image/*,application/pdf', 'max:512' ],

            'current_building_phase_id' => ['sometimes', Rule::in($stagers)],

            'description' => ['sometimes', 'string'],
            
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error' => $validator->errors()->first(),
            'data' => null,
        ], 412));
    }

}
