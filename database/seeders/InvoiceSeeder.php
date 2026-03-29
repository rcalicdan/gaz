<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Driver;
use App\Models\Invoice;
use App\Models\Pickup;
use App\Models\Route;
use App\Models\WasteType;
use App\Enums\PickupStatus;
use App\Enums\KsefStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $pickups = Pickup::where('status', PickupStatus::COMPLETED)
            ->whereDoesntHave('invoice')
            ->get();

        if ($pickups->isEmpty()) {
            $this->command->info('No uninvoiced pickups found. Creating fresh data...');
            
            $client = Client::first() ?? Client::factory()->create();
            $wasteType = WasteType::first() ?? WasteType::factory()->create();
            $driver = Driver::first() ?? Driver::factory()->create();
            $route = Route::first() ?? Route::factory()->create();

            for ($i = 0; $i < 10; $i++) {
                $newPickup = Pickup::create([
                    'client_id' => $client->id,
                    'waste_type_id' => $wasteType->id,
                    'assigned_driver_id' => $driver->id,
                    'route_id' => $route->id,
                    'status' => PickupStatus::COMPLETED,
                    'scheduled_date' => now()->subDays(rand(1, 10)),
                    'actual_pickup_time' => now()->subHours(rand(1, 48)),
                    'waste_quantity' => rand(50, 500),
                    'applied_price_rate' => $client->price_rate ?? 2.50,
                    'certificate_number' => 'CERT-' . strtoupper(Str::random(8)),
                ]);
                $pickups->push($newPickup);
            }
        }

        foreach ($pickups as $index => $pickup) {
            if (Invoice::where('pickup_id', $pickup->id)->exists()) {
                continue;
            }

            $net = round($pickup->waste_quantity * ($pickup->applied_price_rate ?: 2.50), 2);
            $vat = round($net * 0.23, 2);
            $gross = $net + $vat;

            $status = fake()->randomElement([
                KsefStatus::ACCEPTED, 
                KsefStatus::ACCEPTED, 
                KsefStatus::SENT_TO_KSEF, 
                KsefStatus::REJECTED,
                KsefStatus::PENDING
            ]);

            $ksefRef = null;
            $sessionRef = null;

            if ($status === KsefStatus::ACCEPTED) {
                $ksefRef = $pickup->client->vat_id . '-' . now()->format('Ymd') . '-' . strtoupper(Str::random(12)) . '-A1';
                $sessionRef = (string) Str::uuid(); 
            } elseif ($status === KsefStatus::SENT_TO_KSEF) {
                $ksefRef = now()->format('Ymd') . '-EE-' . strtoupper(Str::random(20));
                $sessionRef = (string) Str::uuid();
            }

            Invoice::updateOrCreate(
                ['pickup_id' => $pickup->id],
                [
                    'client_id' => $pickup->client_id,
                    'invoice_number' => 'FV/' . now()->format('Y/m/') . Str::random(5), 
                    'issue_date' => $pickup->scheduled_date,
                    'due_date' => $pickup->scheduled_date->addDays(14),
                    'net_amount' => $net,
                    'vat_amount' => $vat,
                    'gross_amount' => $gross,
                    'ksef_status' => $status,
                    'ksef_reference_number' => $ksefRef,
                    'ksef_session_reference' => $sessionRef,
                    'is_emailed' => ($status === KsefStatus::ACCEPTED),
                ]
            );
        }

        $this->command->info('Invoices seeded successfully!');
    }
}