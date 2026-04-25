<?php

namespace App\Http\Controllers\Api\ClientApp;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientApp\Order\StoreOrderRequest;
use App\Services\ClientAppOrderService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ClientOrderController extends Controller
{
    public function __construct(protected ClientAppOrderService $orderService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $clientId = $request->user()->client_id;

        return response()->json([
            'success' => true,
            'data' => $this->orderService->getClientOrders($clientId)
        ]);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $clientId = $request->user()->client_id;
        $order = $this->orderService->getClientOrder($clientId, $id);

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        $client = $request->user()->client;
        $order = $this->orderService->createOrder($client, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully',
            'data' => $order
        ], 201);
    }

    public function cancel(Request $request, int $id): JsonResponse
    {
        $clientId = $request->user()->client_id;
        $order = $this->orderService->cancelOrder($clientId, $id);

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully',
            'data' => $order
        ]);
    }
}