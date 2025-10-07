<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        $current = $this->route('account') ?? $this->route('user') ?? null;
        $userId  = is_object($current) ? $current->id : (is_numeric($current) ? (int)$current : null);

        $isUpdate = in_array($this->method(), ['PUT', 'PATCH'], true);

        return [
            'first_name'      => ['required', 'string', 'max:100'],
            'middle_name'     => ['nullable', 'string', 'max:100'],
            'last_name'       => ['required', 'string', 'max:100'],
            
            'email' => [
                'nullable', 'email', 'max:255',
                $isUpdate
                    ? Rule::unique('users', 'email')->ignore($userId)
                    : Rule::unique('users', 'email')
            ],

            'contact_number' => [
                'nullable', 'string', 'max:50',
                $isUpdate
                    ? Rule::unique('users', 'contact_number')->ignore($userId)
                    : Rule::unique('users', 'contact_number')
            ],
            
            'password' => $isUpdate
                ? ['nullable', 'string', 'min:8']
                : ['required', 'string', 'min:8'],

            'role' => ['required', 'string', Rule::exists('roles', 'name')],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required'     => 'Please enter the first name.',
            'first_name.string'       => 'First name must be valid text.',
            'first_name.max'          => 'First name may not exceed 100 characters.',

            'middle_name.string'      => 'Middle name must be valid text.',
            'middle_name.max'         => 'Middle name may not exceed 100 characters.',

            'last_name.required'      => 'Please enter the last name.',
            'last_name.string'        => 'Last name must be valid text.',
            'last_name.max'           => 'Last name may not exceed 100 characters.',

            'email.email'             => 'Please enter a valid email address.',
            'email.max'               => 'Email may not exceed 255 characters.',
            'email.unique'            => 'This email address is already registered.',

            'contact_number.string'   => 'Contact number must be valid text.',
            'contact_number.max'      => 'Contact number may not exceed 50 characters.',
            'contact_number.unique'   => 'This contact number is already in use.',

            'password.required'       => 'Please enter a password.',
            'password.min'            => 'Password must be at least 8 characters long.',

            'role.required'           => 'Please select a role for this account.',
            'role.exists'             => 'The selected role is invalid.',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);

        foreach (['first_name','middle_name','last_name','email','contact_number','role'] as $k) {
            if (isset($data[$k]) && is_string($data[$k])) {
                $data[$k] = trim($data[$k]);
            }
        }

        return $data;
    }
}