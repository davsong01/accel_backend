<?php

namespace App\Http\Requests;

use App\Models\EstateConstructionSetting;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreContractUnderConstructionRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $company_id = $this->input('company_id');
        $customer_id = $this->input('customer_id');

        return [
            'total_amount' => [
                'required',
                'min:1',
                'numeric',
                function ($attribute, $value, $fail) {
                    $totalPrice = 0;
                    $settingIds = $this->input('estate_construction_setting_ids', []);
                    $stagers = EstateConstructionSetting::whereIn('id', $settingIds)->get();

                    foreach ($stagers as $product) {
                        $totalPrice += $product->price;
                    }
                    $totalPrice;

                    if ($totalPrice != $value) {
                        $fail("The total amount must be equal to the sum of stager prices which is $totalPrice.");
                    }
                },
            ],

            'estate_construction_setting_ids' => ['required', 'array'],

            'estate_construction_setting_ids.*' => [
                'exists:estate_construction_settings,id,company_id,' . $company_id
            ],

            'customer_id' => ['required', "exists:customers,id,company_id,$company_id"],

            'chart_of_account_id' => ['required', "exists:chart_of_accounts,id,company_id,$company_id"],

            'contract_id' => [
                'required',
                'unique:contract_under_constructions,contract_id',
                Rule::exists('customer_contracts', 'id')
                    ->whereNull('deleted_at')
                    ->where('customer_id', $customer_id)
            ],

            'amount_paid' => ['required', 'min:1', 'numeric', 'lte:total_amount'],

            'payment_slip' => ['file', 'sometimes', 'mimetypes:image/*,application/pdf', 'max:512'],

            'current_building_phase_id' => [
                'sometimes',
                Rule::in($this->input('estate_construction_setting_ids', []))
            ],

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
