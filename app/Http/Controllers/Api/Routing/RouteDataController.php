<?php

namespace App\Http\Controllers\Api\Routing;

use App\Http\Controllers\Controller;
use App\Services\RouteDataService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class RouteDataController extends Controller
{
    use ApiResponseTrait;

    private string $vroomEndpoint = 'http://147.135.252.51:3000';

    public function __construct(
        private RouteDataService $routeDataService
    ) {}

    public function optimize(Request $request): JsonResponse
    {
        try {
            $response = Http::timeout(60) 
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($this->vroomEndpoint, $request->all());

            return response()->json($response->json(), $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Optimization Service Unavailable',
                'message' => $e->getMessage()
            ], 502); 
        }
    }

    public function getDrivers(): JsonResponse
    {
        try {
            $drivers = $this->routeDataService->getAllDrivers();
            return $this->successResponse(['success' => true, 'data' => $drivers]);
        } catch (\Exception $e) {
            return $this->errorResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getOrdersForDriverAndDate(Request $request): JsonResponse
    {
        $request->validate([
            'driver_id' => 'required|integer|exists:drivers,id',
            'date' => 'required|date'
        ]);

        try {
            $pickups = $this->routeDataService->getPickupsForDriverAndDate(
                $request->driver_id,
                $request->date
            );

            return $this->successResponse([
                'success' => true,
                'data' => $pickups,
                'meta' => [
                    'driver_id' => $request->driver_id,
                    'date' => $request->date,
                    'total_count' => count($pickups)
                ]
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getAllOrdersForDateRange(Request $request): JsonResponse
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        try {
            $pickups = $this->routeDataService->getAllPickupsForDateRange(
                $request->start_date,
                $request->end_date
            );

            return $this->successResponse([
                'success' => true,
                'data' => $pickups,
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveRouteOptimization(Request $request): JsonResponse
    {
        $request->validate([
            'driver_id' => 'required|integer|exists:drivers,id',
            'optimization_date' => 'required|date',
            'optimization_result' => 'required|array',
            'order_sequence' => 'nullable|array',
            'total_distance' => 'nullable|numeric',
            'total_time' => 'nullable|integer',
            'is_manual_edit' => 'boolean',
            'manual_modifications' => 'nullable|array'
        ]);

        try {
            $data = $request->all();
            $data['pickup_sequence'] = $data['order_sequence'] ?? [];

            $optimization = $this->routeDataService->saveRouteOptimization($data);
            $isManualOnly = $request->input('manual_modifications.is_manual_only', false);

            return $this->successResponse([
                'success' => true,
                'message' => 'Route optimization saved successfully',
                'data' => $optimization,
                'requires_optimization' => $isManualOnly
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getSavedRouteOptimization(Request $request): JsonResponse
    {
        $request->validate([
            'driver_id' => 'required|integer|exists:drivers,id',
            'date' => 'required|date'
        ]);

        try {
            $optimization = $this->routeDataService->getSavedRouteOptimization(
                $request->driver_id,
                $request->date
            );

            return $this->successResponse([
                'success' => true,
                'data' => $optimization
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function triggerGeocoding(): JsonResponse
    {
        try {
            $result = $this->routeDataService->geocodeMissingCoordinates();
            return $this->successResponse(['success' => true, 'data' => $result]);
        } catch (\Exception $e) {
            return $this->errorResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getRouteStatistics(Request $request): JsonResponse
    {
        try {
            $stats = $this->routeDataService->getRouteStatistics(
                $request->driver_id,
                $request->date
            );
            return $this->successResponse(['success' => true, 'data' => $stats]);
        } catch (\Exception $e) {
            return $this->errorResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    public function getMyRouteOptimizations(Request $request): JsonResponse
    {
         $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        try {
            $user = $request->user();
            if (!$user->isDriver()) {
                 return $this->errorResponse(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            
            $optimizations = $this->routeDataService->getRouteOptimizationsForDriver(
                $user->driver->id,
                $request->start_date,
                $request->end_date
            );
            
            return $this->successResponse(['success' => true, 'data' => $optimizations]);
            
        } catch (\Exception $e) {
             return $this->errorResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}