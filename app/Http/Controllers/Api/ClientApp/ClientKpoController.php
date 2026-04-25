<?php

namespace App\Http\Controllers\Api\ClientApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientApp\Document\IndexKpoRequest;
use App\Services\ClientAppDocumentService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ClientKpoController extends Controller
{
    public function __construct(protected ClientAppDocumentService $documentService)
    {
    }

    public function index(IndexKpoRequest $request): JsonResponse
    {
        $clientId = $request->user()->client_id;
        $kpos = $this->documentService->getKpoDocuments($clientId, $request->validated());

        return response()->json([
            'success' => true,
            'data' => $kpos
        ]);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $clientId = $request->user()->client_id;
        $kpo = $this->documentService->getKpoDocument($clientId, $id);

        return response()->json([
            'success' => true,
            'data' => $kpo
        ]);
    }

    public function download(Request $request, int $id)
    {
        $clientId = $request->user()->client_id;
        
        return $this->documentService->downloadKpoDocument($clientId, $id);
    }
}