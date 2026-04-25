<?php

namespace App\Http\Requests\ClientApp\Waste;

use Illuminate\Foundation\Http\FormRequest;

class IndexWasteRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isClient() && $this->user()->client_id !== null;
    }

    public function rules(): array
    {
        return [
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'waste_type_id' => 'nullable|exists:waste_types,id',
        ];
    }
}