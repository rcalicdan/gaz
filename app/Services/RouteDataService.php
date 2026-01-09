<?php

namespace App\Services;

use App\Models\Driver;
use App\Models\Pickup;
use App\Models\RouteOptimization;
use App\Enums\PickupStatus;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class RouteDataService
{
    protected array $depotCoordinates = [21.0122, 52.2297];
    protected string $vroomEndpoint = 'http://147.135.252.51:3000';

    public function getAllDrivers(): Collection
    {
        return Driver::with('user')
            ->whereHas('user', fn($q) => $q->where('active', true))
            ->get()
            ->map(fn($driver) => [
                'id' => $driver->id,
                'user_id' => $driver->user_id,
                'full_name' => $driver->user->full_name,
                'license_number' => $driver->license_number ?? 'N/A',
                'notes' => $driver->notes,
            ]);
    }

    public function getPickupsForDriverAndDate(int $driverId, string $date): Collection
    {
        return Pickup::with(['client', 'driver.user', 'wasteType'])
            ->where('assigned_driver_id', $driverId)
            ->whereDate('scheduled_date', $date)
            ->get()
            ->map(function ($pickup) {
                return $this->transformPickupForRouteData($pickup);
            });
    }

    public function getAllPickupsForDateRange(?string $startDate = null, ?string $endDate = null): Collection
    {
        $query = Pickup::with(['client', 'driver.user'])
            ->whereNotNull('assigned_driver_id')
            ->whereIn('status', [PickupStatus::SCHEDULED, PickupStatus::IN_PROGRESS]);

        if ($startDate) {
            $query->whereDate('scheduled_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('scheduled_date', '<=', $endDate);
        }

        return $query->orderBy('scheduled_date')
            ->orderBy('assigned_driver_id')
            ->get()
            ->map(function ($pickup) {
                return $this->transformPickupForRouteData($pickup);
            });
    }

    public function saveRouteOptimization(array $data): RouteOptimization
    {
        if (isset($data['manual_modifications']['custom_stops'])) {
            $data['manual_modifications']['custom_stops'] = array_map(function ($stop) {
                if (isset($stop['coordinates'])) {
                    if (is_object($stop['coordinates'])) {
                        $stop['coordinates'] = array_values((array)$stop['coordinates']);
                    }
                  
                    $stop['coordinates'] = array_map('floatval', $stop['coordinates']);
                }
                return $stop;
            }, $data['manual_modifications']['custom_stops']);
        }

        $requiresOptimization = $data['manual_modifications']['requires_optimization'] ?? false;
        $sequence = $data['pickup_sequence'] ?? [];
        $sequence = array_map('strval', $sequence);

        return RouteOptimization::updateOrCreate(
            [
                'driver_id' => $data['driver_id'],
                'optimization_date' => $data['optimization_date']
            ],
            [
                'optimization_result' => $data['optimization_result'],
                'pickup_sequence' => $sequence,
                'total_distance' => $data['total_distance'] ?? null,
                'total_time' => $data['total_time'] ?? null,
                'is_manual_edit' => $data['is_manual_edit'] ?? false,
                'manual_modifications' => $data['manual_modifications'] ?? null,
                'requires_optimization' => $requiresOptimization
            ]
        );
    }

    public function getSavedRouteOptimization(int $driverId, string $date): ?RouteOptimization
    {
        return RouteOptimization::where('driver_id', $driverId)
            ->where('optimization_date', $date)
            ->first();
    }

    private function transformPickupForRouteData(Pickup $pickup): array
    {
        $priority = $this->calculatePickupPriority($pickup);

        return [
            'id' => $pickup->id,
            'driver_id' => $pickup->assigned_driver_id,
            'client_name' => $pickup->client->company_name,
            'address' => $pickup->client->full_address,
            'coordinates' => $pickup->client->coordinates,
            'vroom_coordinates' => $pickup->client->vroom_coordinates,
            'waste_quantity' => (float) $pickup->waste_quantity,
            'status' => $pickup->status->value,
            'status_label' => $pickup->status->label(),
            'priority' => $priority,
            'delivery_date' => $pickup->scheduled_date->format('Y-m-d'),
            'client_phone' => $pickup->client->phone_number ?? null,
            'has_coordinates' => $pickup->client->hasCoordinates(),
            'driver_name' => $pickup->driver_name,
            'is_complaint' => false,
            'waste_type' => $pickup->wasteType->name ?? 'N/A',
        ];
    }

    public function optimizeRouteWithVroom(int $driverId, string $date)
    {
        $pickups = Pickup::with('client')
            ->where('assigned_driver_id', $driverId)
            ->whereDate('scheduled_date', $date)
            ->whereIn('status', [PickupStatus::SCHEDULED, PickupStatus::IN_PROGRESS])
            ->get()
            ->filter(fn($p) => $p->client && $p->client->hasCoordinates());

        if ($pickups->isEmpty()) {
            throw new \Exception("No pickups with valid coordinates found for this driver and date.");
        }

        $payload = $this->buildVroomPayload($driverId, $pickups);

        $response = Http::timeout(30)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($this->vroomEndpoint, $payload);

        if ($response->failed()) {
            throw new \Exception("Optimization failed: " . $response->body());
        }

        return $response->json();
    }

    private function buildVroomPayload(int $driverId, Collection $pickups): array
    {
        $jobs = $pickups->map(function ($pickup) {
            return [
                'id' => $pickup->id,
                'location' => $pickup->client->vroom_coordinates,
                'service' => 600,
                'description' => $pickup->client->company_name,
            ];
        })->values()->toArray();

        $vehicle = [
            'id' => $driverId,
            'profile' => 'driving-car',
            'start' => $this->depotCoordinates,
            'end' => $this->depotCoordinates,
        ];

        return [
            'vehicles' => [$vehicle],
            'jobs' => $jobs,
            'options' => ['g' => true]
        ];
    }

    private function calculatePickupPriority(Pickup $pickup): string
    {
        if ($pickup->waste_quantity > 1000) {
            return 'high';
        }

        if ($pickup->scheduled_date->isPast() && $pickup->status === PickupStatus::SCHEDULED) {
            return 'high';
        }

        return 'medium';
    }

    public function getRouteStatistics(?int $driverId = null, ?string $date = null): array
    {
        $query = Pickup::with(['client'])
            ->whereNotNull('assigned_driver_id');

        if ($driverId) {
            $query->where('assigned_driver_id', $driverId);
        }

        if ($date) {
            $query->whereDate('scheduled_date', $date);
        }

        $pickups = $query->get();

        return [
            'total_pickups' => $pickups->count(),
            'total_waste' => $pickups->sum('waste_quantity'),
            'pickups_with_coords' => $pickups->filter(fn($p) => $p->client && $p->client->hasCoordinates())->count(),
            'status_breakdown' => $pickups->groupBy('status')->map->count(),
            'driver_breakdown' => $pickups->groupBy('assigned_driver_id')->map->count(),
        ];
    }

    public function geocodeMissingCoordinates()
    {
        return ['status' => 'queued'];
    }

    public function getRouteOptimizationsForDriver(int $driverId, ?string $startDate = null, ?string $endDate = null): Collection
    {
        $query = RouteOptimization::with('driver.user')
            ->where('driver_id', $driverId);

        if ($startDate) {
            $query->whereDate('optimization_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('optimization_date', '<=', $endDate);
        }

        return $query->orderBy('optimization_date', 'desc')
            ->get()
            ->map(function ($optimization) {
                $optimizationResult = $optimization->optimization_result ?? [];
                $pickupSequence = $optimization->pickup_sequence ?? [];

                return [
                    'id' => $optimization->id,
                    'driver_id' => $optimization->driver_id,
                    'driver_name' => $optimization->driver->user->full_name ?? 'Unknown Driver',
                    'optimization_date' => $optimization->optimization_date->format('Y-m-d'),
                    'optimization_date_formatted' => $optimization->optimization_date->format('d M Y'),
                    'total_distance' => $optimization->total_distance,
                    'total_time' => $optimization->total_time,
                    'total_orders' => count($pickupSequence),
                    'pickup_sequence' => $pickupSequence,
                    'optimization_result' => $optimizationResult,
                    'is_manual_edit' => $optimization->is_manual_edit,
                    'manual_modifications' => $optimization->manual_modifications,
                    'route_steps' => $optimizationResult['routes'][0]['steps'] ?? [],
                    'geometry' => $optimizationResult['routes'][0]['geometry'] ?? null,

                    'created_at' => $optimization->created_at->toISOString(),
                    'updated_at' => $optimization->updated_at->toISOString(),
                ];
            });
    }

    public function getDriverRouteOptimizationStats(int $driverId, ?string $startDate = null, ?string $endDate = null): array
    {
        $query = RouteOptimization::where('driver_id', $driverId);

        if ($startDate) {
            $query->whereDate('optimization_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('optimization_date', '<=', $endDate);
        }

        $optimizations = $query->get();

        $totalDistance = $optimizations->sum('total_distance');
        $totalTime = $optimizations->sum('total_time');

        $totalPickups = 0;

        foreach ($optimizations as $optimization) {
            $pickupSequence = $optimization->pickup_sequence ?? [];
            $totalPickups += count($pickupSequence);
        }

        $optimizationCount = $optimizations->count();

        return [
            'total_optimizations' => $optimizationCount,
            'total_distance' => round($totalDistance, 2),
            'total_time' => $totalTime,
            'total_pickups_optimized' => $totalPickups,
            'average_distance_per_route' => $optimizationCount > 0 ? round($totalDistance / $optimizationCount, 2) : 0,
            'average_time_per_route' => $optimizationCount > 0 ? round($totalTime / $optimizationCount, 2) : 0,
            'average_pickups_per_route' => $optimizationCount > 0 ? round($totalPickups / $optimizationCount, 2) : 0,

            'manual_edits_count' => $optimizations->where('is_manual_edit', true)->count(),

            'date_range' => [
                'start' => $startDate,
                'end' => $endDate,
            ],

            'period_breakdown' => $this->getPeriodBreakdown($optimizations),
            'distance_breakdown' => $this->getDistanceBreakdown($optimizations)
        ];
    }

    private function getPeriodBreakdown(Collection $optimizations): array
    {
        $today = Carbon::today();
        $thisWeek = Carbon::now()->startOfWeek();
        $thisMonth = Carbon::now()->startOfMonth();

        return [
            'today' => $optimizations->filter(fn($opt) => $opt->optimization_date->isToday())->count(),
            'this_week' => $optimizations->filter(fn($opt) => $opt->optimization_date->gte($thisWeek))->count(),
            'this_month' => $optimizations->filter(fn($opt) => $opt->optimization_date->gte($thisMonth))->count(),
            'last_30_days' => $optimizations->filter(fn($opt) => $opt->optimization_date->gte($today->copy()->subDays(30)))->count(),
        ];
    }

    private function getDistanceBreakdown(Collection $optimizations): array
    {
        return [
            'short_routes' => $optimizations->filter(fn($opt) => ($opt->total_distance ?? 0) < 50)->count(), // < 50km
            'medium_routes' => $optimizations->filter(fn($opt) => ($opt->total_distance ?? 0) >= 50 && ($opt->total_distance ?? 0) < 150)->count(), // 50-150km
            'long_routes' => $optimizations->filter(fn($opt) => ($opt->total_distance ?? 0) >= 150)->count(), // >= 150km
        ];
    }
}
