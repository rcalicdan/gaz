<?php

namespace App\Http\Requests\ClientApp\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:clients,email',
            'password' => 'required|string|min:8|confirmed',
            'company_name' => 'required|string|max:255',
            'vat_id' => 'required|string|max:50|unique:clients,vat_id',
            'registered_street_name' => 'required|string|max:255',
            'registered_city' => 'required|string|max:255',
            'registered_zip_code' => 'required|string|max:20',
            'phone_number' => 'required|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'vat_id.unique' => 'This company is already registered. Please log in, or contact support to request app access.',
            'email.unique' => 'This email is already registered.'
        ];
    }
}