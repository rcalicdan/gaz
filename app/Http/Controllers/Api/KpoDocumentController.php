<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KpoDocument;
use App\Models\Pickup;
use App\Services\KpoPdfService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class KpoDocumentController extends Controller
{
    public function __construct(
        protected KpoPdfService $kpoPdfService
    ) {}

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