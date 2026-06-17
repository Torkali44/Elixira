<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreDxnDistributorApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contract_accepted' => ['required', 'accepted'],
            'sponsor_code' => ['required', 'string', 'max:100'],
            'sponsor_name' => ['required', 'string', 'max:255'],
            'country' => ['required', 'in:KSA,UAE'],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:male,female'],
            'date_of_birth' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'id_number' => ['nullable', 'string', 'max:50', 'required_without:passport_number'],
            'passport_number' => ['nullable', 'string', 'max:50', 'required_without:id_number'],
            'nationality' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'regex:/^\+?[0-9]{8,20}$/'],
            'email' => ['required', 'email', 'max:255'],
            'has_heir' => ['sometimes', 'boolean'],
            'heir_name' => ['nullable', 'required_if:has_heir,1,true', 'string', 'max:255'],
            'heir_relationship' => ['nullable', 'required_if:has_heir,1,true', 'string', 'max:100'],
            'heir_id_number' => ['nullable', 'string', 'max:50'],
            'heir_passport_number' => ['nullable', 'string', 'max:50'],
            'address' => ['required', 'string', 'max:1000'],
            'address_country' => ['required', 'in:KSA,UAE'],
            'address_city' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:20'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! $this->boolean('has_heir')) {
                return;
            }

            if (! filled($this->input('heir_id_number')) && ! filled($this->input('heir_passport_number'))) {
                $validator->errors()->add('heir_id_number', __('dxn_team.validation_heir_id_required'));
            }
        });
    }

    public function messages(): array
    {
        return [
            'contract_accepted.accepted' => __('dxn_team.validation_contract_required'),
            'phone.regex' => __('dxn_team.validation_phone'),
            'email.email' => __('dxn_team.validation_email'),
            'id_number.required_without' => __('dxn_team.validation_id_required'),
            'passport_number.required_without' => __('dxn_team.validation_id_required'),
            'heir_name.required_if' => __('dxn_team.validation_heir_name'),
            'heir_relationship.required_if' => __('dxn_team.validation_heir_relationship'),
        ];
    }
}
