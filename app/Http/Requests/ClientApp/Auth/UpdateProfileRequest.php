<?php

namespace App\Http\Requests\ClientApp\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isClient() && $this->user()->client_id !== null;
    }

    public function rules(): array
    {
        return [
            'premises_street_name' => 'sometimes|string|max:255',
            'premises_street_number' => 'sometimes|string|max:50|nullable',
            'premises_city' => 'sometimes|string|max:255',
            'premises_zip_code' => 'sometimes|string|max:20',
            'premises_province' => 'sometimes|string|max:255|nullable',
            'contact_person' => 'sometimes|string|max:255',
        ];
    }
}