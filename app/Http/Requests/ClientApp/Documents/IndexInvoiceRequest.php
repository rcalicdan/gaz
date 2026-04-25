<?php

namespace App\Http\Requests\ClientApp\Document;

use Illuminate\Foundation\Http\FormRequest;

class IndexInvoiceRequest extends FormRequest
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
            'status' => 'nullable|string|in:paid,unpaid,overdue',
        ];
    }
}