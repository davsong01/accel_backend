<?php

namespace App\Http\Requests;

use App\Models\ContractUnderConstruction;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateContractUnderConstructionRequest extends FormRequest
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

        // Merge the contract data into the request data
        $contract = $this->route('contract');
        if ($contract instanceof ContractUnderConstruction) {
            $data = array_merge($data, ['contract' => $contract]);
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
        $contract = $this->contract;
        $balance = intval($contract->total_amount) - intval($contract->amount_paid);

        return [
            'chart_of_account_id' => ['required_with:amount_paid', "exists:chart_of_accounts,id,company_id,$company_id"],
            'amount_paid' => ['required_with:amount_paid', 'min:1', 'numeric', "lte:$balance"],
            'payment_slip' => ['file', 'sometimes', 'mimetypes:image/*,application/pdf', 'max:512' ],
            'current_building_phase_id' => [
                'sometimes',
                Rule::in($contract->estate_construction_setting_ids)
            ],
            'completed_building_phases' => ['sometimes', 'array'],
            'completed_building_phases.*' => [
                Rule::in($contract->estate_construction_setting_ids)
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
