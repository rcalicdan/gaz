<?php

namespace App\Services;

use App\Models\Client;

class ClientService
{
    public function storeNewClient(array $data): Client
    {
        return Client::create($data);
    }

    public function updateClientInformation(Client $client, array $data): Client
    {
        $client->update($data);
        return $client->fresh();
    }

    public function deleteClient(Client $client): bool
    {
        return $client->delete();
    }
}