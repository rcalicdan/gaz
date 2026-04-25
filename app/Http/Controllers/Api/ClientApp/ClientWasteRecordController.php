<?php

namespace App\Http\Controllers\Api\ClientApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientApp\Waste\IndexWasteRecordRequest;
use App\Http\Requests\ClientApp\Waste\IndexWasteStatisticRequest;
use App\Http\Requests\ClientApp\Waste\ExportWasteRecordRequest;
use App\Services\ClientAppWasteService;
use Illuminate\Http\JsonResponse;

class ClientWasteRecordController extends Controller
{
    public function __construct(protected ClientAppWasteService $wasteService)
    {
    }

    public function index(IndexWasteRecordRequest $request): JsonResponse
    {
        $clientId = $request->user()->client_id;
        $records = $this->wasteService->getWasteRecords($clientId, $request->validated());

        return response()->json([
            'success' => true,
            'data' => $records
        ]);
    }

    public function statistics(IndexWasteStatisticRequest $request): JsonResponse
    {
        $clientId = $request->user()->client_id;
        $stats = $this->wasteService->getWasteStatistics($clientId, $request->validated());

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    public function export(ExportWasteRecordRequest $request)
    {
        $clientId = $request->user()->client_id;
        $validated = $request->validated();
        
        return $this->wasteService->exportWasteRecords($clientId, $validated, $validated['format']);
    }
}