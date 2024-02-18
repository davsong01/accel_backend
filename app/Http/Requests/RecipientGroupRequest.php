<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class RecipientGroupRequest extends FormRequest
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

        return [
            'name' => 'required|string|max:250|min:2',
            'all_customers' => 'boolean',
            'customer_status' => [
                'exclude_if:all_customers,true', 'required_if:all_customers,false', 'array', 'bail', 'min:1',
                function ($attribute, $value, $fail) {
                    $target = 'subscriber';
                    $key = 'subscriber_status';

                    if (in_array($target, $value ?? [])) {
                        if (!$this->input($key)) {
                            $fail($attribute, "$key is required when customer_status contains $target'.");
                        }
                    }
                },
            ],
            'customer_status.*' => ['string', 'distinct'],
            'subscriber_status' => [
                'exclude_if:all_customers,true', 'bail', 'array', 'min:1',
                function ($attribute, $value, $fail) {
                    $target = 'completed';
                    $key = 'completed_subscriber_status';

                    if (in_array($target, $value ?? [])) {
                        if (!$this->input($key)) {
                            $fail($attribute, "$key is required when customer_status contains '$target'.");
                        }
                    }
                },
            ],
            'subscriber_status.*' => ['string', 'distinct',],
            'completed_subscriber_status' => ['exclude_if:all_customers,true', 'array', 'min:1'],
            'completed_subscriber_status.*' => 'in:inactive,active',
            'city_ids' => ['array', 'required_if:all_customers,false', 'exclude_if:all_customers,true'],
            'city_ids.*' => "exists:cities,id,company_id,$company_id",
            'location_ids' => [
                'array', 'required_if:all_customers,false', 'exclude_if:all_customers,true'
            ],
            'location_ids.*' => [
                Rule::exists('locations', 'id')->where(function ($query) use ($company_id) {
                    $query->where('company_id', $company_id)->whereIn('city_id', $this->city_ids);
                }),
            ],
            'estate_ids' => [
                'array', 'required_if:all_customers,false', 'exclude_if:all_customers,true',
            ],
            'estate_ids.*' => [
                Rule::exists('estates', 'id')->where(function ($query) use ($company_id) {
                    $query->where('company_id', $company_id)->whereIn('location_id', $this->location_ids);
                }),
            ],

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
