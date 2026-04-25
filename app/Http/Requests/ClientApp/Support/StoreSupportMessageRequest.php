<?php

namespace App\Http\Requests\ClientApp\Support;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupportMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isClient() && $this->user()->client_id !== null;
    }

    public function rules(): array
    {
        return [
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ];
    }
}