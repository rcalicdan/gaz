<?php

namespace App\Livewire\PriceLists;

use App\Services\PriceListService;
use App\Models\PriceList;
use App\Models\WasteType;
use Livewire\Component;

class CreatePage extends Component
{
    public $name = '';
    public $description = '';
    public $is_active = true;
    public $items = [];

    protected PriceListService $priceListService;

    public function boot(PriceListService $priceListService)
    {
        $this->priceListService = $priceListService;
    }

    public function mount()
    {
        $this->addItem();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:price_lists,name',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
            'items' => 'required|array|min:1',
            'items.*.waste_type_id' => 'required|exists:waste_types,id|distinct',
            'items.*.base_price' => 'required|numeric|min:0|max:999999.99',
            'items.*.currency' => 'required|string|in:PLN,EUR,USD',
            'items.*.tax_rate' => 'required|numeric|min:0|max:1',
            'items.*.unit_type' => 'required|in:per_pickup,per_kg,per_ton,per_box',
            'items.*.min_quantity' => 'nullable|numeric|min:0',
            'items.*.max_quantity' => 'nullable|numeric|min:0|gte:items.*.min_quantity',
        ];
    }

    public function validationAttributes()
    {
        $attributes = [
            'name' => 'price list name',
            'description' => 'description',
            'is_active' => 'active status',
        ];

        foreach ($this->items as $index => $item) {
            $attributes["items.{$index}.waste_type_id"] = "waste type #" . ($index + 1);
            $attributes["items.{$index}.base_price"] = "base price #" . ($index + 1);
            $attributes["items.{$index}.currency"] = "currency #" . ($index + 1);
            $attributes["items.{$index}.tax_rate"] = "tax rate #" . ($index + 1);
            $attributes["items.{$index}.unit_type"] = "unit type #" . ($index + 1);
            $attributes["items.{$index}.min_quantity"] = "min quantity #" . ($index + 1);
            $attributes["items.{$index}.max_quantity"] = "max quantity #" . ($index + 1);
        }

        return $attributes;
    }

    public function addItem()
    {
        $this->items[] = [
            'waste_type_id' => '',
            'base_price' => '',
            'currency' => 'PLN',
            'tax_rate' => '0.23',
            'unit_type' => 'per_pickup',
            'min_quantity' => '',
            'max_quantity' => '',
        ];
    }

    public function removeItem($index)
    {
        if (count($this->items) > 1) {
            unset($this->items[$index]);
            $this->items = array_values($this->items);
        }
    }

    public function save()
    {
        $this->authorize('create', PriceList::class);
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'items' => $this->items,
        ];

        try {
            $priceList = $this->priceListService->createPriceList($data);

            session()->flash('success', __('Price list has been successfully created!'));

            return redirect()->route('price-lists.index');
        } catch (\Exception $e) {
            session()->flash('error', __('An error occurred while creating the price list. Please try again.'));
        }
    }

    public function render()
    {
        $this->authorize('create', PriceList::class);
        $wasteTypes = WasteType::orderBy('name')->get();
        
        return view('livewire.price-lists.create-page', [
            'wasteTypes' => $wasteTypes
        ]);
    }
}