<?php

namespace App\Services;

use App\Models\PriceList;
use App\Models\PriceListItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PriceListService
{
    public function getAllPriceLists(bool $paginate = false, int $perPage = 15): Collection|LengthAwarePaginator
    {
        $query = PriceList::query()->with('items.wasteType')->latest();

        return $paginate ? $query->paginate($perPage) : $query->get();
    }

    public function getActivePriceLists(): Collection
    {
        return PriceList::where('is_active', true)
            ->with('items.wasteType')
            ->latest()
            ->get();
    }

    public function findPriceList(int $id): ?PriceList
    {
        return PriceList::with(['items.wasteType', 'clients'])->find($id);
    }

    public function createPriceList(array $data): PriceList
    {
        return DB::transaction(function () use ($data) {
            $priceList = PriceList::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ]);

            if (isset($data['items']) && is_array($data['items'])) {
                $this->syncPriceListItems($priceList, $data['items']);
            }

            return $priceList->load('items.wasteType');
        });
    }

    public function updatePriceList(int $id, array $data): ?PriceList
    {
        return DB::transaction(function () use ($id, $data) {
            $priceList = PriceList::find($id);

            if (!$priceList) {
                return null;
            }

            $priceList->update([
                'name' => $data['name'] ?? $priceList->name,
                'description' => $data['description'] ?? $priceList->description,
                'is_active' => $data['is_active'] ?? $priceList->is_active,
            ]);

            if (isset($data['items']) && is_array($data['items'])) {
                $this->syncPriceListItems($priceList, $data['items']);
            }

            return $priceList->load('items.wasteType');
        });
    }

    public function deletePriceList(int $id): bool
    {
        $priceList = PriceList::find($id);

        if (!$priceList) {
            return false;
        }

        return $priceList->delete();
    }

    protected function syncPriceListItems(PriceList $priceList, array $items): void
    {
        $priceList->items()->delete();

        foreach ($items as $item) {
            $priceList->items()->create([
                'waste_type_id' => $item['waste_type_id'],
                'base_price' => $item['base_price'],
                'currency' => $item['currency'] ?? 'PLN',
                'tax_rate' => $item['tax_rate'] ?? 0.23,
                'unit_type' => $item['unit_type'] ?? 'per_pickup',
                'min_quantity' => $item['min_quantity'] ?? null,
                'max_quantity' => $item['max_quantity'] ?? null,
            ]);
        }
    }

    public function getPriceForWasteType(int $priceListId, int $wasteTypeId, ?float $quantity = null): ?PriceListItem
    {
        $query = PriceListItem::where('price_list_id', $priceListId)
            ->where('waste_type_id', $wasteTypeId);

        if ($quantity !== null) {
            $query->where(function ($q) use ($quantity) {
                $q->where(function ($subQ) use ($quantity) {
                    $subQ->whereNull('min_quantity')
                        ->orWhere('min_quantity', '<=', $quantity);
                })
                ->where(function ($subQ) use ($quantity) {
                    $subQ->whereNull('max_quantity')
                        ->orWhere('max_quantity', '>=', $quantity);
                });
            });
        }

        return $query->first();
    }

    public function duplicatePriceList(int $id, string $newName): ?PriceList
    {
        return DB::transaction(function () use ($id, $newName) {
            $originalPriceList = PriceList::with('items')->find($id);

            if (!$originalPriceList) {
                return null;
            }

            $newPriceList = PriceList::create([
                'name' => $newName,
                'description' => $originalPriceList->description,
                'is_active' => false,
            ]);

            foreach ($originalPriceList->items as $item) {
                $newPriceList->items()->create($item->only([
                    'waste_type_id',
                    'base_price',
                    'currency',
                    'tax_rate',
                    'unit_type',
                    'min_quantity',
                    'max_quantity',
                ]));
            }

            return $newPriceList->load('items.wasteType');
        });
    }
}