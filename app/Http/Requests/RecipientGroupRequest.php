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

            'all_customers' => 'required|boolean',

            'customer_status' => [
                'exclude_if:all_customers,true',
                'required_without_all:all_customers,city_ids,location_ids,estate_ids',
                'array',
                'bail',
            ],

            'customer_status.*' => ['string', 'distinct', 'min:1'],

            'subscriber_status' => [
                'bail',
                'array',
                'exclude_if:all_customers,true',
                'required_if_array_contains:customer_status,subscriber',
            ],

            'subscriber_status.*' => ['string', 'distinct', 'min:1'],

            'completed_subscriber_status' => [
                'bail',
                'array',
                'exclude_if:all_customers,true',
                'required_if_array_contains:subscriber_status,completed',
            ],

            'completed_subscriber_status.*' => 'in:inactive,active',

            'city_ids' => [
                'array',
                'required_without_all:all_customers,customer_status,location_ids,estate_ids',
                'exclude_if:all_customers,true'
            ],

            'city_ids.*' => "exists:cities,id,company_id,$company_id",

            'location_ids' => [
                'array',
                'exclude_if:all_customers,true'
            ],

            'location_ids.*' => [
                Rule::exists('locations', 'id')->where(function ($query) use ($company_id) {
                    $query->where('company_id', $company_id);

                    if($this->city_ids){
                        $query->whereIn('city_id', $this->city_ids);
                    }
                }),
            ],

            'estate_ids' => [
                'array',
                'exclude_if:all_customers,true',
            ],

            'estate_ids.*' => [
                Rule::exists('estates', 'id')->where(function ($query) use ($company_id) {
                    $query->where('company_id', $company_id);
                    if($this->location_ids){
                        $query->whereIn('location_id', $this->location_ids);
                    }
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

    public function withValidator($validator)
    {
        $validator->addExtension('required_if_array_contains', function ($attribute, $value, $parameters) {
            [$field, $contains] = $parameters;

            if (in_array($contains, (array) $this->input($field, []))) {
                if (!count((array) $value)) {
                    return false;
                }
            }
            return true;
        });

        $validator->addReplacer('required_if_array_contains', function ($message, $attribute, $rule, $parameters) {
            return str_replace([':attribute', ':value'], [$attribute, $parameters[0]], $message);
        });
    }


    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {

        return [
            'subscriber_status.required_if_array_contains' => ':attribute is required and can\'t be less than 1 when :value contains subscriber.',
        ];
    }
}
