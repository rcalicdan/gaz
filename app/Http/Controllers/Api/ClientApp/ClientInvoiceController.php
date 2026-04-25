<?php

namespace App\Http\Controllers\Api\ClientApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientApp\Document\IndexInvoiceRequest;
use App\Services\ClientAppDocumentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ClientInvoiceController extends Controller
{
    public function __construct(protected ClientAppDocumentService $documentService) {}

    public function index(IndexInvoiceRequest $request): JsonResponse
    {
        $clientId = $request->user()->client_id;
        $invoices = $this->documentService->getInvoices($clientId, $request->validated());

        return response()->json([
            'success' => true,
            'data' => $invoices
        ]);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $clientId = $request->user()->client_id;
        $invoice = $this->documentService->getInvoice($clientId, $id);

        return response()->json([
            'success' => true,
            'data' => $invoice
        ]);
    }

    public function download(Request $request, int $id)
    {
        $clientId = $request->user()->client_id;

        return $this->documentService->downloadInvoice($clientId, $id);
    }
}
