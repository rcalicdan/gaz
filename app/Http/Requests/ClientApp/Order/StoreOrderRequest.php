<?php

namespace App\Http\Requests\ClientApp\Order;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isClient() && $this->user()->client_id !== null;
    }

    public function rules(): array
    {
        return [
            'waste_type_id' => 'required|exists:waste_types,id',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'driver_note' => 'nullable|string|max:1000',
            'boxes' => 'nullable|array',
            'boxes.*.box_number' => 'required|string|max:255',
            'boxes.*.note' => 'nullable|string|max:500',
        ];
    }
}