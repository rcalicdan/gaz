<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Pickup;
use App\Enums\PickupStatus;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PickupFrequencyService
{
    public function calculateNextPickupDate(Client $client, ?Carbon $fromDate = null): ?Carbon
    {
        if (!$client->pickup_frequency_days) {
            return null;
        }

        $fromDate = $fromDate ?? now();
        
        if ($client->last_pickup_date) {
            $nextDate = Carbon::parse($client->last_pickup_date)
                ->addDays($client->pickup_frequency_days);
            
            while ($nextDate->isPast()) {
                $nextDate->addDays($client->pickup_frequency_days);
            }
            
            return $nextDate;
        }
        
        return $fromDate->copy()->addDays($client->pickup_frequency_days);
    }

    public function isPickupDue(Client $client, ?Carbon $checkDate = null): bool
    {
        if (!$client->pickup_frequency_days) {
            return false;
        }

        $checkDate = $checkDate ?? now();
        $nextPickupDate = $this->calculateNextPickupDate($client);
        
        if (!$nextPickupDate) {
            return false;
        }

        return $nextPickupDate->lte($checkDate);
    }

    public function getClientsDueForPickup(?Carbon $date = null): Collection
    {
        $date = $date ?? now();
        
        return Client::whereNotNull('pickup_frequency_days')
            ->with(['defaultWasteType', 'priceList'])
            ->get()
            ->filter(function ($client) use ($date) {
                return $this->isPickupDue($client, $date);
            });
    }

    public function getClientsDueInDateRange(Carbon $startDate, Carbon $endDate): Collection
    {
        $clientsDue = collect();
        
        $clients = Client::whereNotNull('pickup_frequency_days')
            ->with(['defaultWasteType', 'priceList'])
            ->get();

        foreach ($clients as $client) {
            $nextPickupDate = $this->calculateNextPickupDate($client, $startDate);
            
            if ($nextPickupDate && 
                $nextPickupDate->between($startDate, $endDate)) {
                $clientsDue->push([
                    'client' => $client,
                    'scheduled_date' => $nextPickupDate,
                ]);
            }
        }

        return $clientsDue->sortBy('scheduled_date');
    }

    public function getOverduePickups(): Collection
    {
        return Pickup::where('scheduled_date', '<', now()->toDateString())
            ->whereIn('status', [
                PickupStatus::SCHEDULED->value,
                PickupStatus::IN_PROGRESS->value,
            ])
            ->with(['client', 'wasteType', 'driver'])
            ->orderBy('scheduled_date')
            ->get();
    }

    public function getClientsWithOverduePickups(): Collection
    {
        return Client::whereNotNull('pickup_frequency_days')
            ->with(['defaultWasteType', 'priceList'])
            ->get()
            ->filter(function ($client) {
                if (!$client->last_pickup_date) {
                    return false;
                }

                $daysSinceLastPickup = Carbon::parse($client->last_pickup_date)
                    ->diffInDays(now());
                
                return $daysSinceLastPickup > $client->pickup_frequency_days;
            })
            ->map(function ($client) {
                $daysOverdue = Carbon::parse($client->last_pickup_date)
                    ->diffInDays(now()) - $client->pickup_frequency_days;
                
                return [
                    'client' => $client,
                    'days_overdue' => $daysOverdue,
                    'last_pickup_date' => $client->last_pickup_date,
                    'expected_pickup_date' => Carbon::parse($client->last_pickup_date)
                        ->addDays($client->pickup_frequency_days),
                ];
            })
            ->sortByDesc('days_overdue');
    }

    public function updateLastPickupDate(Client $client, Carbon $pickupDate): void
    {
        $client->update([
            'last_pickup_date' => $pickupDate,
        ]);
    }

    public function autoSchedulePickups(Carbon $forDate): Collection
    {
        $scheduledPickups = collect();
        
        $clientsDue = $this->getClientsDueForPickup($forDate);

        DB::transaction(function () use ($clientsDue, $forDate, &$scheduledPickups) {
            foreach ($clientsDue as $client) {
                $existingPickup = Pickup::where('client_id', $client->id)
                    ->where('scheduled_date', $forDate->toDateString())
                    ->first();

                if (!$existingPickup) {
                    $pickup = Pickup::create([
                        'client_id' => $client->id,
                        'scheduled_date' => $forDate,
                        'status' => PickupStatus::SCHEDULED,
                        'waste_type_id' => $client->default_waste_type_id,
                        'applied_price_rate' => $client->price_rate,
                    ]);

                    $scheduledPickups->push($pickup);
                }
            }
        });

        return $scheduledPickups;
    }

    public function generatePickupSchedule(Carbon $startDate, Carbon $endDate): Collection
    {
        $schedule = collect();
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $clientsDue = $this->getClientsDueForPickup($currentDate);
            
            foreach ($clientsDue as $client) {
                $schedule->push([
                    'date' => $currentDate->copy(),
                    'client' => $client,
                    'waste_type' => $client->defaultWasteType,
                    'frequency_days' => $client->pickup_frequency_days,
                ]);
            }
            
            $currentDate->addDay();
        }

        return $schedule->groupBy(function ($item) {
            return $item['date']->toDateString();
        });
    }

    public function getClientPickupStats(Client $client, ?int $months = 6): array
    {
        $startDate = now()->subMonths($months);
        
        $pickups = Pickup::where('client_id', $client->id)
            ->where('scheduled_date', '>=', $startDate)
            ->whereIn('status', [
                PickupStatus::COMPLETED->value,
                PickupStatus::INVOICED->value,
            ])
            ->orderBy('scheduled_date')
            ->get();

        $totalPickups = $pickups->count();
        $averageInterval = 0;
        
        if ($totalPickups > 1) {
            $intervals = [];
            for ($i = 1; $i < $totalPickups; $i++) {
                $intervals[] = Carbon::parse($pickups[$i]->scheduled_date)
                    ->diffInDays(Carbon::parse($pickups[$i - 1]->scheduled_date));
            }
            $averageInterval = count($intervals) > 0 ? array_sum($intervals) / count($intervals) : 0;
        }

        return [
            'total_pickups' => $totalPickups,
            'average_interval_days' => round($averageInterval, 1),
            'configured_frequency_days' => $client->pickup_frequency_days,
            'compliance_rate' => $client->pickup_frequency_days && $averageInterval > 0 
                ? round(($client->pickup_frequency_days / $averageInterval) * 100, 1)
                : null,
            'last_pickup_date' => $client->last_pickup_date,
            'next_scheduled_date' => $this->calculateNextPickupDate($client),
            'is_overdue' => $this->isPickupOverdue($client),
        ];
    }

    public function isPickupOverdue(Client $client): bool
    {
        if (!$client->pickup_frequency_days || !$client->last_pickup_date) {
            return false;
        }

        $expectedDate = Carbon::parse($client->last_pickup_date)
            ->addDays($client->pickup_frequency_days);
        
        return $expectedDate->isPast();
    }

    public function bulkUpdateFrequency(array $clientIds, int $frequencyDays): int
    {
        return Client::whereIn('id', $clientIds)
            ->update(['pickup_frequency_days' => $frequencyDays]);
    }

    public function recommendPickupFrequency(Client $client, ?int $months = 6): ?int
    {
        $startDate = now()->subMonths($months);
        
        $pickups = Pickup::where('client_id', $client->id)
            ->where('scheduled_date', '>=', $startDate)
            ->whereIn('status', [
                PickupStatus::COMPLETED->value,
                PickupStatus::INVOICED->value,
            ])
            ->orderBy('scheduled_date')
            ->get();

        if ($pickups->count() < 3) {
            return null; // Not enough data
        }

        $intervals = [];
        for ($i = 1; $i < $pickups->count(); $i++) {
            $intervals[] = Carbon::parse($pickups[$i]->scheduled_date)
                ->diffInDays(Carbon::parse($pickups[$i - 1]->scheduled_date));
        }

        if (empty($intervals)) {
            return null;
        }

        sort($intervals);
        $count = count($intervals);
        $middle = floor($count / 2);
        
        if ($count % 2 == 0) {
            $median = ($intervals[$middle - 1] + $intervals[$middle]) / 2;
        } else {
            $median = $intervals[$middle];
        }

        return (int) round($median);
    }

    public function getClientsByFrequency(): Collection
    {
        return Client::whereNotNull('pickup_frequency_days')
            ->select('pickup_frequency_days', DB::raw('count(*) as client_count'))
            ->groupBy('pickup_frequency_days')
            ->orderBy('pickup_frequency_days')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->pickup_frequency_days => $item->client_count];
            });
    }

    public function calculateExpectedPickups(Carbon $startDate, Carbon $endDate): int
    {
        $clients = Client::whereNotNull('pickup_frequency_days')->get();
        $totalExpectedPickups = 0;
        $days = $startDate->diffInDays($endDate) + 1;

        foreach ($clients as $client) {
            if ($client->pickup_frequency_days > 0) {
                $expectedPickups = floor($days / $client->pickup_frequency_days);
                $totalExpectedPickups += $expectedPickups;
            }
        }

        return $totalExpectedPickups;
    }
}