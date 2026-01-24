<?php

namespace App\Http\Controllers\Api;

use App\Enums\DocumentType;
use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\Models\KpoDocument;
use App\Models\Pickup;
use App\Services\KpoEmailService;
use App\Services\KpoPdfService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: 'KPO Documents',
    description: 'KPO (Waste Transfer Declaration) document management endpoints'
)]
class KpoDocumentController extends Controller
{
    public function __construct(
        protected KpoPdfService $kpoPdfService,
        protected KpoEmailService $kpoEmailService
    ) {}

    #[OA\Get(
        path: '/api/kpo-documents/{kpoDocument}',
        summary: 'Get KPO document details',
        security: [['bearerAuth' => []]],
        tags: ['KPO Documents'],
        parameters: [
            new OA\Parameter(
                name: 'kpoDocument',
                in: 'path',
                required: true,
                description: 'KPO Document ID',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'KPO document details retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'kpo_number', type: 'string', example: 'KPO-2026-00001'),
                                new OA\Property(property: 'waste_code', type: 'string', example: '30 01 25'),
                                new OA\Property(property: 'quantity', type: 'number', format: 'float', example: 150.5),
                                new OA\Property(property: 'additional_notes', type: 'string', nullable: true),
                                new OA\Property(property: 'pdf_url', type: 'string', nullable: true),
                                new OA\Property(property: 'pdf_path', type: 'string', nullable: true),
                                new OA\Property(property: 'pdf_version', type: 'integer', example: 1),
                                new OA\Property(property: 'pdf_generated_at', type: 'string', format: 'date-time', nullable: true),
                                new OA\Property(property: 'pdf_size', type: 'string', example: '256 KB'),
                                new OA\Property(property: 'pdf_exists', type: 'boolean', example: true),
                                new OA\Property(property: 'needs_regeneration', type: 'boolean', example: false),
                                new OA\Property(property: 'is_emailed', type: 'boolean', example: true),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
                            ],
                            type: 'object'
                        ),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'KPO document not found'),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
    public function show(KpoDocument $kpoDocument): JsonResponse
    {
        $kpoDocument->load(['pickup', 'client']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $kpoDocument->id,
                'kpo_number' => $kpoDocument->kpo_number,
                'waste_code' => $kpoDocument->waste_code,
                'quantity' => $kpoDocument->quantity,
                'additional_notes' => $kpoDocument->additional_notes,
                'pdf_url' => $kpoDocument->pdf_url,
                'pdf_path' => $kpoDocument->pdf_path,
                'pdf_version' => $kpoDocument->pdf_version,
                'pdf_generated_at' => $kpoDocument->pdf_generated_at,
                'pdf_size' => $kpoDocument->getPdfSizeForHumans(),
                'pdf_exists' => $kpoDocument->hasPdf(),
                'needs_regeneration' => $kpoDocument->needsRegeneration(),
                'is_emailed' => $kpoDocument->is_emailed,
                'created_at' => $kpoDocument->created_at,
                'updated_at' => $kpoDocument->updated_at,
                'pickup' => $kpoDocument->pickup ? [
                    'id' => $kpoDocument->pickup->id,
                    'scheduled_date' => $kpoDocument->pickup->scheduled_date,
                    'status' => $kpoDocument->pickup->status,
                ] : null,
                'client' => $kpoDocument->client ? [
                    'id' => $kpoDocument->client->id,
                    'company_name' => $kpoDocument->client->company_name,
                    'email' => $kpoDocument->client->email,
                ] : null,
            ]
        ]);
    }

    #[OA\Get(
        path: '/api/kpo-documents',
        summary: 'List KPO documents with filters',
        security: [['bearerAuth' => []]],
        tags: ['KPO Documents'],
        parameters: [
            new OA\Parameter(
                name: 'pickup_id',
                in: 'query',
                description: 'Filter by pickup ID',
                required: false,
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'client_id',
                in: 'query',
                description: 'Filter by client ID',
                required: false,
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'is_emailed',
                in: 'query',
                description: 'Filter by email status',
                required: false,
                schema: new OA\Schema(type: 'boolean')
            ),
            new OA\Parameter(
                name: 'from_date',
                in: 'query',
                description: 'Filter from date (YYYY-MM-DD)',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date')
            ),
            new OA\Parameter(
                name: 'to_date',
                in: 'query',
                description: 'Filter to date (YYYY-MM-DD)',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date')
            ),
            new OA\Parameter(
                name: 'search',
                in: 'query',
                description: 'Search by KPO number',
                required: false,
                schema: new OA\Schema(type: 'string')
            ),
            new OA\Parameter(
                name: 'per_page',
                in: 'query',
                description: 'Items per page',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 15)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of KPO documents',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(
                            property: 'meta',
                            properties: [
                                new OA\Property(property: 'current_page', type: 'integer'),
                                new OA\Property(property: 'last_page', type: 'integer'),
                                new OA\Property(property: 'per_page', type: 'integer'),
                                new OA\Property(property: 'total', type: 'integer'),
                            ],
                            type: 'object'
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $query = KpoDocument::with(['pickup', 'client']);

        if ($request->has('pickup_id')) {
            $query->where('pickup_id', $request->pickup_id);
        }

        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->has('is_emailed')) {
            $query->where('is_emailed', $request->boolean('is_emailed'));
        }

        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->has('search')) {
            $query->where('kpo_number', 'like', '%' . $request->search . '%');
        }

        $documents = $query->latest()->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => $documents->map(function ($doc) {
                return [
                    'id' => $doc->id,
                    'kpo_number' => $doc->kpo_number,
                    'waste_code' => $doc->waste_code,
                    'quantity' => $doc->quantity,
                    'pdf_url' => $doc->pdf_url,
                    'pdf_version' => $doc->pdf_version,
                    'is_emailed' => $doc->is_emailed,
                    'needs_regeneration' => $doc->needsRegeneration(),
                    'created_at' => $doc->created_at,
                    'client' => [
                        'id' => $doc->client->id,
                        'company_name' => $doc->client->company_name,
                    ],
                ];
            }),
            'meta' => [
                'current_page' => $documents->currentPage(),
                'last_page' => $documents->lastPage(),
                'per_page' => $documents->perPage(),
                'total' => $documents->total(),
            ]
        ]);
    }

    #[OA\Post(
        path: '/api/kpo-documents/generate-for-pickup/{pickup}',
        summary: 'Generate KPO PDF for a pickup',
        security: [['bearerAuth' => []]],
        tags: ['KPO Documents'],
        parameters: [
            new OA\Parameter(
                name: 'pickup',
                in: 'path',
                required: true,
                description: 'Pickup ID',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'PDF generated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'PDF generated successfully'),
                        new OA\Property(
                            property: 'data',
                            properties: [
                                new OA\Property(property: 'kpo_document_id', type: 'integer'),
                                new OA\Property(property: 'kpo_number', type: 'string'),
                                new OA\Property(property: 'pdf_url', type: 'string'),
                                new OA\Property(property: 'pdf_path', type: 'string'),
                                new OA\Property(property: 'pdf_version', type: 'integer'),
                                new OA\Property(property: 'path', type: 'string'),
                            ],
                            type: 'object'
                        ),
                    ]
                )
            ),
            new OA\Response(response: 403, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Pickup not found'),
            new OA\Response(response: 500, description: 'Failed to generate PDF')
        ]
    )]
    public function generatePdfForPickup(Pickup $pickup): JsonResponse
    {
        if (!$this->canAccessPickup($pickup)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You can only generate PDFs for your assigned pickups.',
            ], 403);
        }

        try {
            $kpoDocument = KpoDocument::firstOrCreate(
                ['pickup_id' => $pickup->id],
                [
                    'client_id' => $pickup->client_id,
                    'waste_code' => $pickup->wasteType?->code,
                    'quantity' => $pickup->waste_quantity,
                    'kpo_number' => $this->generateKpoNumber(),
                ]
            );

            $path = $this->kpoPdfService->generateKpoDocument($kpoDocument);

            return response()->json([
                'success' => true,
                'message' => 'PDF generated successfully',
                'data' => [
                    'kpo_document_id' => $kpoDocument->id,
                    'kpo_number' => $kpoDocument->kpo_number,
                    'pdf_url' => $kpoDocument->fresh()->pdf_url,
                    'pdf_path' => $kpoDocument->fresh()->pdf_path,
                    'pdf_version' => $kpoDocument->fresh()->pdf_version,
                    'path' => $path
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate PDF',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    #[OA\Post(
        path: '/api/kpo-documents/generate-my-pickup',
        summary: 'Generate KPO PDF for driver\'s assigned pickup',
        security: [['bearerAuth' => []]],
        tags: ['KPO Documents'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['pickup_id'],
                properties: [
                    new OA\Property(property: 'pickup_id', type: 'integer', example: 1),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'PDF generated successfully'),
            new OA\Response(response: 403, description: 'Forbidden - Not a driver or pickup not assigned'),
            new OA\Response(response: 404, description: 'Pickup not found'),
            new OA\Response(response: 500, description: 'Failed to generate PDF')
        ]
    )]
    public function generatePdfForMyPickup(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user->isDriver()) {
            return response()->json([
                'success' => false,
                'message' => 'Only drivers can use this endpoint.',
            ], 403);
        }

        $validated = $request->validate([
            'pickup_id' => 'required|exists:pickups,id'
        ]);

        $pickup = Pickup::findOrFail($validated['pickup_id']);

        if ($pickup->assigned_driver_id !== $user->driver?->id) {
            return response()->json([
                'success' => false,
                'message' => 'This pickup is not assigned to you.',
            ], 403);
        }

        try {
            $kpoDocument = KpoDocument::firstOrCreate(
                ['pickup_id' => $pickup->id],
                [
                    'client_id' => $pickup->client_id,
                    'waste_code' => $pickup->wasteType?->code,
                    'quantity' => $pickup->waste_quantity,
                    'kpo_number' => $this->generateKpoNumber(),
                ]
            );

            $path = $this->kpoPdfService->generateKpoDocument($kpoDocument);

            return response()->json([
                'success' => true,
                'message' => 'PDF generated successfully',
                'data' => [
                    'kpo_document_id' => $kpoDocument->id,
                    'kpo_number' => $kpoDocument->kpo_number,
                    'pdf_url' => $kpoDocument->fresh()->pdf_url,
                    'pdf_path' => $kpoDocument->fresh()->pdf_path,
                    'pdf_version' => $kpoDocument->fresh()->pdf_version,
                    'path' => $path,
                    'pickup' => [
                        'id' => $pickup->id,
                        'client_name' => $pickup->client->company_name,
                        'scheduled_date' => $pickup->scheduled_date,
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate PDF',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    #[OA\Post(
        path: '/api/kpo-documents/{kpoDocument}/generate-pdf',
        summary: 'Generate or regenerate PDF for KPO document',
        security: [['bearerAuth' => []]],
        tags: ['KPO Documents'],
        parameters: [
            new OA\Parameter(
                name: 'kpoDocument',
                in: 'path',
                required: true,
                description: 'KPO Document ID',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'PDF generated successfully'),
            new OA\Response(response: 404, description: 'KPO document not found'),
            new OA\Response(response: 500, description: 'Failed to generate PDF')
        ]
    )]
    public function generatePdf(KpoDocument $kpoDocument): JsonResponse
    {
        try {
            $path = $this->kpoPdfService->generateKpoDocument($kpoDocument);

            $kpoDocument->refresh();

            return response()->json([
                'success' => true,
                'message' => 'PDF generated successfully',
                'data' => [
                    'kpo_document_id' => $kpoDocument->id,
                    'kpo_number' => $kpoDocument->kpo_number,
                    'pdf_url' => $kpoDocument->pdf_url,
                    'pdf_path' => $kpoDocument->pdf_path,
                    'pdf_version' => $kpoDocument->pdf_version,
                    'pdf_generated_at' => $kpoDocument->pdf_generated_at,
                    'path' => $path
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate PDF',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    #[OA\Get(
        path: '/api/kpo-documents/{kpoDocument}/download',
        summary: 'Download KPO PDF document',
        security: [['bearerAuth' => []]],
        tags: ['KPO Documents'],
        parameters: [
            new OA\Parameter(
                name: 'kpoDocument',
                in: 'path',
                required: true,
                description: 'KPO Document ID',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'PDF file downloaded',
                content: new OA\MediaType(
                    mediaType: 'application/pdf',
                    schema: new OA\Schema(type: 'string', format: 'binary')
                )
            ),
            new OA\Response(response: 404, description: 'PDF file not found'),
            new OA\Response(response: 500, description: 'Failed to download PDF')
        ]
    )]
    public function downloadPdf(KpoDocument $kpoDocument): BinaryFileResponse|JsonResponse
    {
        try {
            if (!$kpoDocument->pdf_path || !Storage::exists($kpoDocument->pdf_path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'PDF file not found'
                ], 404);
            }

            $filename = "KPO_{$kpoDocument->kpo_number}.pdf";

            return response()->download(
                Storage::path($kpoDocument->pdf_path),
                $filename,
                [
                    'Content-Type' => 'application/pdf',
                ]
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to download PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    #[OA\Get(
        path: '/api/kpo-documents/{kpoDocument}/preview',
        summary: 'Preview KPO PDF document in browser',
        security: [['bearerAuth' => []]],
        tags: ['KPO Documents'],
        parameters: [
            new OA\Parameter(
                name: 'kpoDocument',
                in: 'path',
                required: true,
                description: 'KPO Document ID',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'PDF file preview',
                content: new OA\MediaType(
                    mediaType: 'application/pdf',
                    schema: new OA\Schema(type: 'string', format: 'binary')
                )
            ),
            new OA\Response(response: 404, description: 'PDF file not found'),
            new OA\Response(response: 500, description: 'Failed to preview PDF')
        ]
    )]
    public function previewPdf(KpoDocument $kpoDocument): BinaryFileResponse|JsonResponse
    {
        try {
            if (!$kpoDocument->pdf_path || !Storage::exists($kpoDocument->pdf_path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'PDF file not found'
                ], 404);
            }

            $filename = "KPO_{$kpoDocument->kpo_number}.pdf";

            return response()->file(
                Storage::path($kpoDocument->pdf_path),
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="' . $filename . '"'
                ]
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to preview PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    #[OA\Post(
        path: '/api/kpo-documents/{kpoDocument}/email-to-client',
        summary: 'Email KPO document to registered client',
        security: [['bearerAuth' => []]],
        tags: ['KPO Documents'],
        parameters: [
            new OA\Parameter(
                name: 'kpoDocument',
                in: 'path',
                required: true,
                description: 'KPO Document ID',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'custom_message',
                        type: 'string',
                        description: 'Custom message to include in email',
                        maxLength: 1000,
                        nullable: true
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Email sent successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(
                            property: 'data',
                            properties: [
                                new OA\Property(property: 'kpo_number', type: 'string'),
                                new OA\Property(property: 'recipient_email', type: 'string'),
                                new OA\Property(property: 'sent_at', type: 'string', format: 'date-time'),
                            ],
                            type: 'object'
                        ),
                    ]
                )
            ),
            new OA\Response(response: 400, description: 'Client email not found'),
            new OA\Response(response: 500, description: 'Failed to send email')
        ]
    )]
    public function emailToClient(KpoDocument $kpoDocument, Request $request): JsonResponse
    {
        if (!$kpoDocument->client || !$kpoDocument->client->email) {
            return response()->json([
                'success' => false,
                'message' => 'Client email not found. Cannot send document.'
            ], 400);
        }

        $validated = $request->validate([
            'custom_message' => 'nullable|string|max:1000'
        ]);

        $success = $this->kpoEmailService->sendToClient(
            $kpoDocument,
            $validated['custom_message'] ?? null
        );

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'KPO document sent successfully to ' . $kpoDocument->client->email,
                'data' => [
                    'kpo_number' => $kpoDocument->kpo_number,
                    'recipient_email' => $kpoDocument->client->email,
                    'sent_at' => now()->toIso8601String()
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to send KPO document. Please check logs.'
        ], 500);
    }

    #[OA\Post(
        path: '/api/kpo-documents/{kpoDocument}/email-to-custom',
        summary: 'Email KPO document to custom email address (Admin/Employee only)',
        security: [['bearerAuth' => []]],
        tags: ['KPO Documents'],
        parameters: [
            new OA\Parameter(
                name: 'kpoDocument',
                in: 'path',
                required: true,
                description: 'KPO Document ID',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['recipient_email', 'authorization_reason'],
                properties: [
                    new OA\Property(
                        property: 'recipient_email',
                        type: 'string',
                        format: 'email',
                        example: 'custom@example.com'
                    ),
                    new OA\Property(
                        property: 'custom_message',
                        type: 'string',
                        maxLength: 1000,
                        nullable: true
                    ),
                    new OA\Property(
                        property: 'authorization_reason',
                        type: 'string',
                        description: 'Reason for sending to custom email (GDPR audit)',
                        maxLength: 500,
                        example: 'Client requested delivery to alternative email'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Email sent successfully'),
            new OA\Response(response: 403, description: 'Unauthorized - Admin/Employee only'),
            new OA\Response(response: 500, description: 'Failed to send email')
        ]
    )]
    public function emailToCustomAddress(KpoDocument $kpoDocument, Request $request): JsonResponse
    {
        $user = auth()->user();
        if (!$user->canManage()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only administrators and employees can send documents to custom email addresses.'
            ], 403);
        }

        $validated = $request->validate([
            'recipient_email' => 'required|email',
            'custom_message' => 'nullable|string|max:1000',
            'authorization_reason' => 'required|string|max:500'
        ]);

        $success = $this->kpoEmailService->sendToCustomEmail(
            $kpoDocument,
            $validated['recipient_email'],
            $validated['custom_message'] ?? null,
            $validated['authorization_reason']
        );

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'KPO document sent successfully to ' . $validated['recipient_email'],
                'data' => [
                    'kpo_number' => $kpoDocument->kpo_number,
                    'recipient_email' => $validated['recipient_email'],
                    'sent_at' => now()->toIso8601String(),
                    'authorized_by' => $user->full_name
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to send KPO document. Please check logs.'
        ], 500);
    }

    #[OA\Get(
        path: '/api/kpo-documents/{kpoDocument}/email-history',
        summary: 'Get email sending history for KPO document (GDPR audit trail)',
        security: [['bearerAuth' => []]],
        tags: ['KPO Documents'],
        parameters: [
            new OA\Parameter(
                name: 'kpoDocument',
                in: 'path',
                required: true,
                description: 'KPO Document ID',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Email history retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer'),
                                    new OA\Property(property: 'recipient_email', type: 'string'),
                                    new OA\Property(property: 'status', type: 'object'),
                                    new OA\Property(property: 'sent_at', type: 'string', format: 'date-time'),
                                    new OA\Property(property: 'error_message', type: 'string', nullable: true),
                                    new OA\Property(property: 'sent_by', type: 'object', nullable: true),
                                ],
                                type: 'object'
                            )
                        ),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'KPO document not found')
        ]
    )]
    public function emailHistory(KpoDocument $kpoDocument): JsonResponse
    {
        $history = $this->kpoEmailService->getEmailHistory($kpoDocument);

        return response()->json([
            'success' => true,
            'data' => $history->map(function ($log) {
                return [
                    'id' => $log->id,
                    'recipient_email' => $log->recipient_email,
                    'status' => [
                        'value' => $log->status->value,
                        'label' => $log->status->label(),
                        'color' => $log->status->color(),
                        'icon' => $log->status->icon(),
                        'is_successful' => $log->status->isSuccessful(),
                        'needs_retry' => $log->status->needsRetry(),
                    ],
                    'sent_at' => $log->sent_at?->toIso8601String(),
                    'error_message' => $log->error_message,
                    'sent_by' => $log->sentBy ? [
                        'id' => $log->sentBy->id,
                        'name' => $log->sentBy->full_name,
                        'email' => $log->sentBy->email
                    ] : null,
                    'created_at' => $log->created_at->toIso8601String()
                ];
            })
        ]);
    }

    #[OA\Post(
        path: '/api/kpo-documents/email-logs/{emailLog}/retry',
        summary: 'Retry failed email sending',
        security: [['bearerAuth' => []]],
        tags: ['KPO Documents'],
        parameters: [
            new OA\Parameter(
                name: 'emailLog',
                in: 'path',
                required: true,
                description: 'Email Log ID',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'custom_message', type: 'string', maxLength: 1000, nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Email retry sent successfully'),
            new OA\Response(response: 400, description: 'Email cannot be retried or not a KPO document'),
            new OA\Response(response: 500, description: 'Failed to retry email')
        ]
    )]
    public function retryEmail(EmailLog $emailLog, Request $request): JsonResponse
    {
        if ($emailLog->document_type !== DocumentType::KPO) {
            return response()->json([
                'success' => false,
                'message' => 'This endpoint only handles KPO documents.'
            ], 400);
        }

        if (!$emailLog->status->needsRetry()) {
            return response()->json([
                'success' => false,
                'message' => 'This email cannot be retried. Status: ' . $emailLog->status->label()
            ], 400);
        }

        $validated = $request->validate([
            'custom_message' => 'nullable|string|max:1000'
        ]);

        $success = $this->kpoEmailService->retryEmail(
            $emailLog,
            $validated['custom_message'] ?? null
        );

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Email retry sent successfully to ' . $emailLog->recipient_email,
                'data' => [
                    'original_email_log_id' => $emailLog->id,
                    'recipient_email' => $emailLog->recipient_email,
                    'retried_at' => now()->toIso8601String()
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to retry email. Please check logs.'
        ], 500);
    }

    #[OA\Get(
        path: '/api/kpo-documents/{kpoDocument}/email-statistics',
        summary: 'Get email statistics for KPO document',
        security: [['bearerAuth' => []]],
        tags: ['KPO Documents'],
        parameters: [
            new OA\Parameter(
                name: 'kpoDocument',
                in: 'path',
                required: true,
                description: 'KPO Document ID',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Email statistics retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            properties: [
                                new OA\Property(property: 'kpo_number', type: 'string'),
                                new OA\Property(
                                    property: 'statistics',
                                    properties: [
                                        new OA\Property(property: 'total_sent', type: 'integer'),
                                        new OA\Property(property: 'successful', type: 'integer'),
                                        new OA\Property(property: 'failed', type: 'integer'),
                                        new OA\Property(property: 'bounced', type: 'integer'),
                                        new OA\Property(property: 'last_sent_at', type: 'string', format: 'date-time', nullable: true),
                                        new OA\Property(property: 'unique_recipients', type: 'integer'),
                                    ],
                                    type: 'object'
                                ),
                            ],
                            type: 'object'
                        ),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'KPO document not found')
        ]
    )]
    public function emailStatistics(KpoDocument $kpoDocument): JsonResponse
    {
        $statistics = $this->kpoEmailService->getEmailStatistics($kpoDocument);

        return response()->json([
            'success' => true,
            'data' => [
                'kpo_number' => $kpoDocument->kpo_number,
                'statistics' => $statistics
            ]
        ]);
    }

    #[OA\Get(
        path: '/api/kpo-documents/pickup/{pickup}/download',
        summary: 'Download KPO PDF by pickup',
        security: [['bearerAuth' => []]],
        tags: ['KPO Documents'],
        parameters: [
            new OA\Parameter(
                name: 'pickup',
                in: 'path',
                required: true,
                description: 'Pickup ID',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'PDF downloaded'),
            new OA\Response(response: 403, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'KPO document not found for this pickup')
        ]
    )]
    public function downloadPdfByPickup(Pickup $pickup): BinaryFileResponse|JsonResponse
    {
        if (!$this->canAccessPickup($pickup)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 403);
        }

        $kpoDocument = $pickup->kpoDocument;

        if (!$kpoDocument) {
            return response()->json([
                'success' => false,
                'message' => 'KPO document not found for this pickup.'
            ], 404);
        }

        return $this->downloadPdf($kpoDocument);
    }

    #[OA\Get(
        path: '/api/kpo-documents/pickup/{pickup}/preview',
        summary: 'Preview KPO PDF by pickup',
        security: [['bearerAuth' => []]],
        tags: ['KPO Documents'],
        parameters: [
            new OA\Parameter(
                name: 'pickup',
                in: 'path',
                required: true,
                description: 'Pickup ID',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'PDF preview'),
            new OA\Response(response: 403, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'KPO document not found for this pickup')
        ]
    )]
    public function previewPdfByPickup(Pickup $pickup): BinaryFileResponse|JsonResponse
    {
        if (!$this->canAccessPickup($pickup)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 403);
        }

        $kpoDocument = $pickup->kpoDocument;

        if (!$kpoDocument) {
            return response()->json([
                'success' => false,
                'message' => 'KPO document not found for this pickup.'
            ], 404);
        }

        return $this->previewPdf($kpoDocument);
    }

    private function canAccessPickup(Pickup $pickup): bool
    {
        $user = auth()->user();

        if ($user->isAdmin() || $user->isEmployee()) {
            return true;
        }

        if ($user->isDriver()) {
            return $pickup->assigned_driver_id === $user->driver?->id;
        }

        return false;
    }

    private function generateKpoNumber(): string
    {
        return DB::transaction(function () {
            $year = now()->year;

            $lastKpo = KpoDocument::whereYear('created_at', $year)
                ->whereNotNull('kpo_number')
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first();

            if ($lastKpo && $lastKpo->kpo_number) {
                $parts = explode('-', $lastKpo->kpo_number);
                $lastNumber = (int) end($parts);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }

            return sprintf('KPO-%d-%05d', $year, $newNumber);
        });
    }
}