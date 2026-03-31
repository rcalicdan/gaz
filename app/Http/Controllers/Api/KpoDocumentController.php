<?php

namespace App\Http\Controllers\Api;

use App\Enums\KsefStatus;
use App\Enums\PickupStatus;
use App\Http\Controllers\Controller;
use App\Models\Pickup;
use App\Services\InvoiceService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'Invoices',
    description: 'Invoice generation and KSeF submission endpoints'
)]
class InvoiceController extends Controller
{
    public function __construct(protected InvoiceService $invoiceService) {}

    #[OA\Post(
        path: '/api/invoices/generate-for-pickup/{pickup}',
        summary: 'Manually generate and submit an invoice for a completed pickup',
        description: 'Creates an invoice for the given pickup and immediately dispatches it to KSeF. Drivers may only invoice their own assigned pickups. Admins and employees may invoice any completed pickup. Returns 409 if an invoice already exists for this pickup.',
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
            new OA\Response(
                response: 201,
                description: 'Invoice created and queued for KSeF submission',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Invoice generated successfully.'),
                        new OA\Property(property: 'invoice_id', type: 'integer', example: 17),
                        new OA\Property(property: 'invoice_number', type: 'string', example: 'FV/2026/03/0017'),
                        new OA\Property(
                            property: 'ksef_status',
                            type: 'string',
                            example: 'pending',
                            description: 'Initial KSeF status. Will transition to sent_to_ksef and eventually accepted or rejected asynchronously.'
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Driver attempting to invoice a pickup not assigned to them',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'You are not assigned to this pickup.'),
                    ]
                )
            ),
            new OA\Response(
                response: 409,
                description: 'Invoice already exists for this pickup',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Invoice already exists for this pickup.'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Pickup is not in a completed state',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Cannot invoice a pickup that is not completed.'),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Unexpected server error during invoice generation or KSeF dispatch',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Failed to generate invoice.'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
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