<?php

namespace App\Services;

use App\Models\WasteType;

class WasteTypeService
{
    public function getAllWasteTypes()
    {
        $query = WasteType::query()
            ->when(request('code'), function ($query) {
                $query->where('code', 'like', '%'.request('code').'%');
            })
            ->when(request('name'), function ($query) {
                $query->where('name', 'like', '%'.request('name').'%');
            })
            ->when(request('description'), function ($query) {
                $query->where('description', 'like', '%'.request('description').'%');
            });

        return $query->paginate(30);
    }

    public function getWasteTypeInformation(WasteType $wasteType)
    {
        $wasteType->load(['clients', 'pickups']);
    }

    public function storeNewWasteType(array $data)
    {
        $wasteType = WasteType::create($data);

        return $wasteType;
    }

    public function updateWasteTypeInformation(WasteType $wasteType, array $data)
    {
        $wasteType->update($data);

        return $wasteType;
    }

    public function deleteWasteTypeInformation(WasteType $wasteType)
    {
        $wasteType->delete();

        return true;
    }
}