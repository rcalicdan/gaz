<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KpoDocument;
use App\Services\KpoPdfService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class KpoDocumentController extends Controller
{
    public function __construct(
        protected KpoPdfService $kpoPdfService
    ) {}

    public function generatePdf(KpoDocument $kpoDocument): JsonResponse
    {
        try {
            $path = $this->kpoPdfService->generateKpoDocument($kpoDocument);
            
            return response()->json([
                'success' => true,
                'message' => 'PDF generated successfully',
                'data' => [
                    'pdf_url' => $kpoDocument->fresh()->pdf_url,
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

    public function downloadPdf(KpoDocument $kpoDocument): Response
    {
        try {
            $this->kpoPdfService->downloadPdf($kpoDocument);
            exit;
        } catch (\Exception $e) {
            abort(500, 'Failed to download PDF');
        }
    }

    public function previewPdf(KpoDocument $kpoDocument): Response
    {
        try {
            $this->kpoPdfService->previewPdf($kpoDocument);
            exit;
        } catch (\Exception $e) {
            abort(500, 'Failed to preview PDF');
        }
    }
}