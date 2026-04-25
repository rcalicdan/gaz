<?php

namespace App\Http\Requests\ClientApp\Waste;

use Illuminate\Foundation\Http\FormRequest;

class IndexWasteStatisticRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isClient() && $this->user()->client_id !== null;
    }

    public function rules(): array
    {
        return [
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'month' => 'nullable|integer|min:1|max:12',
        ];
    }
}