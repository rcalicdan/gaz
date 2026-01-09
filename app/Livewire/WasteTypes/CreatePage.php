<?php

namespace App\Livewire\WasteTypes;

use App\Services\WasteTypeService;
use App\Models\WasteType;
use Livewire\Component;

class CreatePage extends Component
{
    public $code = '';
    public $name = '';
    public $description = '';

    protected WasteTypeService $wasteTypeService;

    public function boot(WasteTypeService $wasteTypeService)
    {
        $this->wasteTypeService = $wasteTypeService;
    }

    public function rules()
    {
        return [
            'code' => 'required|string|max:255|unique:waste_types,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function validationAttributes()
    {
        return [
            'code' => 'code',
            'name' => 'name',
            'description' => 'description',
        ];
    }

    public function save()
    {
        $this->authorize('create', WasteType::class);
        $this->validate();

        $data = [
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
        ];

        try {
            $wasteType = $this->wasteTypeService->storeNewWasteType($data);

            session()->flash('success', __('Waste type has been successfully created!'));

            return redirect()->route('waste-types.index');
        } catch (\Exception $e) {
            session()->flash('error', __('An error occurred while creating the waste type. Please try again.'));
        }
    }

    public function render()
    {
        $this->authorize('create', WasteType::class);
        return view('livewire.waste-types.create-page');
    }
}