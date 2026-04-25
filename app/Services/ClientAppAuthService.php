<?php

namespace App\Services;

use App\Models\Client;
use App\Models\User;
use App\Enums\UserRole;
use App\Enums\PickupFrequency;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Exceptions\AccountDeactivatedException;

class ClientAppAuthService
{
    public function registerClient(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $client = Client::create([
                'company_name' => $data['company_name'],
                'vat_id' => $data['vat_id'],
                'email' => $data['email'],
                'registered_street_name' => $data['registered_street_name'],
                'registered_city' => $data['registered_city'],
                'registered_zip_code' => $data['registered_zip_code'],
                'pickup_frequency' => PickupFrequency::ON_DEMAND,
                'contact_person' => $data['first_name'] . ' ' . $data['last_name'],
            ]);

            $client->phoneNumbers()->create([
                'phone_number' => $data['phone_number'],
                'label' => 'Main',
                'is_primary' => true,
            ]);

            return User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => UserRole::CLIENT,
                'client_id' => $client->id,
                'active' => false,
            ]);
        });
    }

    public function generateToken(User $user): string
    {
        return $user->createToken('MobileAppToken')->accessToken;
    }

    public function authenticateClient(array $credentials): ?User
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return null;
        }

        if (!$user->isClient()) {
            return null;
        }

        if (!$user->isActive()) {
            throw new AccountDeactivatedException();
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return null;
        }

        return $user;
    }
}
