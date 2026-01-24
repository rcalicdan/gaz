<?php

namespace App\Http\Controllers\Api\Routing;

use App\Http\Controllers\Controller;
use App\Models\RouteOptimization;
use App\Services\RouteDataService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Route Data', description: 'Route data management endpoints')]
#[OA\Tag(name: 'Route Optimization', description: 'Route optimization endpoints')]
class RouteDataController extends Controller
{
    use ApiResponseTrait;

    private string $vroomEndpoint = 'http://147.135.252.51:3000';

    public function __construct(
        private RouteDataService $routeDataService
    ) {}

    #[OA\Post(
        path: '/api/vroom/optimize',
        summary: 'Optimize routes using VROOM engine',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                description: 'VROOM optimization payload'
            )
        ),
        tags: ['Route Optimization'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Optimization successful',
                content: new OA\JsonContent(type: 'object')
            ),
            new OA\Response(response: 502, description: 'Optimization service unavailable'),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
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

    #[OA\Get(
        path: '/api/route-data/drivers',
        summary: 'Get all drivers',
        security: [['bearerAuth' => []]],
        tags: ['Route Data'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Drivers retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(type: 'object')
                        )
                    ]
                )
            ),
            new OA\Response(response: 500, description: 'Server error'),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
    public function getDrivers(): JsonResponse
    {
        try {
            $drivers = $this->routeDataService->getAllDrivers();
            return $this->successResponse(['success' => true, 'data' => $drivers]);
        } catch (\Exception $e) {
            return $this->errorResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    #[OA\Get(
        path: '/api/route-data/orders',
        summary: 'Get orders for specific driver and date',
        security: [['bearerAuth' => []]],
        tags: ['Route Data'],
        parameters: [
            new OA\Parameter(
                name: 'driver_id',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                example: 1
            ),
            new OA\Parameter(
                name: 'date',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'date'),
                example: '2024-01-15'
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Orders retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(type: 'object')
                        ),
                        new OA\Property(
                            property: 'meta',
                            properties: [
                                new OA\Property(property: 'driver_id', type: 'integer'),
                                new OA\Property(property: 'date', type: 'string'),
                                new OA\Property(property: 'total_count', type: 'integer')
                            ],
                            type: 'object'
                        )
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 500, description: 'Server error'),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
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

    #[OA\Get(
        path: '/api/route-data/all-orders',
        summary: 'Get all orders for date range',
        security: [['bearerAuth' => []]],
        tags: ['Route Data'],
        parameters: [
            new OA\Parameter(
                name: 'start_date',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date'),
                example: '2024-01-01'
            ),
            new OA\Parameter(
                name: 'end_date',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date'),
                example: '2024-01-31'
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Orders retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(type: 'object')
                        )
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 500, description: 'Server error'),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
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

    #[OA\Post(
        path: '/api/route-data/save-optimization',
        summary: 'Save route optimization result',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['driver_id', 'optimization_date'],
                properties: [
                    new OA\Property(property: 'driver_id', type: 'integer', example: 1),
                    new OA\Property(property: 'optimization_date', type: 'string', format: 'date', example: '2024-01-15'),
                    new OA\Property(property: 'optimization_result', type: 'object'),
                    new OA\Property(
                        property: 'order_sequence',
                        type: 'array',
                        items: new OA\Items(type: 'integer')
                    ),
                    new OA\Property(property: 'total_distance', type: 'number', example: 45.5),
                    new OA\Property(property: 'total_time', type: 'integer', example: 3600),
                    new OA\Property(property: 'is_manual_edit', type: 'boolean', example: false),
                    new OA\Property(property: 'manual_modifications', type: 'object')
                ]
            )
        ),
        tags: ['Route Optimization'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Route optimization saved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'data', type: 'object'),
                        new OA\Property(property: 'requires_optimization', type: 'boolean')
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 500, description: 'Server error'),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
    public function saveRouteOptimization(Request $request): JsonResponse
    {
        $request->validate([
            'driver_id' => 'required|integer|exists:drivers,id',
            'optimization_date' => 'required|date',
            'optimization_result' => 'nullable|array', 
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

    #[OA\Get(
        path: '/api/route-data/saved-optimization',
        summary: 'Get saved route optimization',
        security: [['bearerAuth' => []]],
        tags: ['Route Optimization'],
        parameters: [
            new OA\Parameter(
                name: 'driver_id',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                example: 1
            ),
            new OA\Parameter(
                name: 'date',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'date'),
                example: '2024-01-15'
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Saved optimization retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'object')
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 500, description: 'Server error'),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
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

    #[OA\Post(
        path: '/api/route-data/geocode',
        summary: 'Trigger geocoding for missing coordinates',
        security: [['bearerAuth' => []]],
        tags: ['Route Data'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Geocoding completed successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'object')
                    ]
                )
            ),
            new OA\Response(response: 500, description: 'Server error'),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
    public function triggerGeocoding(): JsonResponse
    {
        try {
            $result = $this->routeDataService->geocodeMissingCoordinates();
            return $this->successResponse(['success' => true, 'data' => $result]);
        } catch (\Exception $e) {
            return $this->errorResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    #[OA\Get(
        path: '/api/route-data/statistics',
        summary: 'Get route statistics',
        security: [['bearerAuth' => []]],
        tags: ['Route Data'],
        parameters: [
            new OA\Parameter(
                name: 'driver_id',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer'),
                example: 1
            ),
            new OA\Parameter(
                name: 'date',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date'),
                example: '2024-01-15'
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Statistics retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'object')
                    ]
                )
            ),
            new OA\Response(response: 500, description: 'Server error'),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
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

    #[OA\Get(
        path: '/api/route-data/driver-optimizations',
        summary: 'Get route optimizations for authenticated driver',
        security: [['bearerAuth' => []]],
        tags: ['Route Optimization'],
        parameters: [
            new OA\Parameter(
                name: 'start_date',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date'),
                example: '2024-01-01'
            ),
            new OA\Parameter(
                name: 'end_date',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', format: 'date'),
                example: '2024-01-31'
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Optimizations retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(type: 'object')
                        )
                    ]
                )
            ),
            new OA\Response(response: 403, description: 'Unauthorized - User is not a driver'),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 500, description: 'Server error'),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
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

    #[OA\Delete(
        path: '/api/route-data/delete-optimization',
        summary: 'Delete saved route optimization',
        security: [['bearerAuth' => []]],
        tags: ['Route Optimization'],
        parameters: [
            new OA\Parameter(
                name: 'driver_id',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'integer'),
                example: 1
            ),
            new OA\Parameter(
                name: 'date',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'string', format: 'date'),
                example: '2024-01-15'
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Route optimization deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'deleted', type: 'boolean')
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 500, description: 'Server error'),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
    public function deleteSavedRouteOptimization(Request $request): JsonResponse
    {
        $request->validate([
            'driver_id' => 'required|integer|exists:drivers,id',
            'date' => 'required|date'
        ]);

        try {
            $deleted = RouteOptimization::where('driver_id', $request->driver_id)
                ->where('optimization_date', $request->date)
                ->delete();

            return $this->successResponse([
                'success' => true,
                'message' => 'Route optimization deleted successfully',
                'deleted' => $deleted > 0
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}