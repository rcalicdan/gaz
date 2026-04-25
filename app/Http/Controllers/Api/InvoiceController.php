<?php

namespace App\Http\Controllers\Api;

use App\Enums\PickupStatus;
use App\Http\Controllers\Controller;
use App\Models\Pickup;
use App\Services\InvoiceService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Invoices', description: 'Invoice generation and KSeF submission endpoints')]
class InvoiceController extends Controller
{
    public function __construct(protected InvoiceService $invoiceService) {}

    #[OA\Post(
        path: '/api/invoices/generate-for-pickup/{pickup}',
        summary: 'Manually generate and submit an invoice for a completed pickup',
        description: 'Creates an invoice for the given pickup and immediately dispatches it to KSeF.',
        security: [['bearerAuth' => []]],
        tags: ['Invoices'],
        parameters: [
            new OA\Parameter(
                name: 'pickup',
                in: 'path',
                required: true,
                description: 'Pickup ID',
                schema: new OA\Schema(type: 'integer', example: 42)
            )
        ],
        responses: [
            new OA\Response(response: 201, description: 'Invoice created successfully'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 409, description: 'Conflict - Invoice exists'),
            new OA\Response(response: 422, description: 'Unprocessable Entity'),
            new OA\Response(response: 500, description: 'Server Error'),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
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
            return response()->json(['message' => 'Invoice already exists for this pickup.'], 409);
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