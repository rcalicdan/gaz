<?php

namespace Database\Seeders;

use App\Models\Pickup;
use App\Models\PickupBox;
use Illuminate\Database\Seeder;

class PickupBoxSeeder extends Seeder
{
    public function run(): void
    {
        $pickups = Pickup::all();

        if ($pickups->isEmpty()) {
            $this->command->warn('No pickups found. Run PickupSeeder first.');
            return;
        }

        foreach ($pickups as $pickup) {
            $boxCount = rand(1, 5);

            for ($i = 1; $i <= $boxCount; $i++) {
                PickupBox::create([
                    'pickup_id' => $pickup->id,
                    'box_number' => 'BOX-' . str_pad($pickup->id, 4, '0', STR_PAD_LEFT) . '-' . $i,
                    'note' => rand(0, 1) ? 'Box in good condition' : null,
                ]);
            }
        }
    }
}