<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Pickup;
use App\Enums\KsefStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'invoice_number' => null, 
            'issue_date' => now(),
            'due_date' => now()->addDays(14),
            'net_amount' => 0,
            'vat_amount' => 0, 
            'gross_amount' => 0, 
            'ksef_status' => KsefStatus::PENDING,
            'ksef_reference_number' => null,
            'is_emailed' => false,
        ];
    }

    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'ksef_status' => KsefStatus::ACCEPTED,
            'ksef_reference_number' => '1111111111-20260325-' . strtoupper(Str::random(12)) . '-AF',
        ]);
    }

    /**
     * Simulate an invoice currently being processed.
     */
    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'ksef_status' => KsefStatus::SENT_TO_KSEF,
            'ksef_reference_number' => '20260325-SE-' . strtoupper(Str::random(10)) . '-' . strtoupper(Str::random(10)) . '-FF',
        ]);
    }
}