<?php

namespace App\Http\Controllers\Api;

use App\Enums\PickupStatus;
use App\Http\Controllers\Controller;
use App\Models\Pickup;
use App\Services\InvoiceService;
use Illuminate\Http\JsonResponse;

class InvoiceController extends Controller
{
    public function __construct(protected InvoiceService $invoiceService) {}

    public function generateForPickup(Pickup $pickup): JsonResponse
    {
        $user = auth()->user();

        if ($user->isDriver() && $pickup->assigned_driver_id !== $user->driver?->id) {
            return response()->json(['message' => 'You are not assigned to this pickup.'], 403);
        }

        if ($pickup->status !== PickupStatus::COMPLETED) {
            return response()->json(['message' => 'Cannot invoice a pickup that is not completed.'], 422);
        }

        if ($pickup->invoice()->exists()) {
            return response()->json(['message' => "Invoice already exists for this pickup."], 409);
        }

        try {
            $invoice = $this->invoiceService->generateForPickup($pickup, forceKsefDispatch: true);

            return response()->json([
                'message'        => 'Invoice generated successfully.',
                'invoice_id'     => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
                'ksef_status'    => $invoice->ksef_status,
            ], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to generate invoice.'], 500);
        }
    }
}