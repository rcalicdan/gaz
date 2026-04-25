<?php

namespace App\Http\Controllers\Api\ClientApp;

use App\Http\Controllers\Controller;
use App\Services\ClientAppOrderService;
use Illuminate\Http\JsonResponse;

class ClientWasteTypeController extends Controller
{
    public function __construct(protected ClientAppOrderService $orderService)
    {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->orderService->getWasteTypes()
        ]);
    }
}