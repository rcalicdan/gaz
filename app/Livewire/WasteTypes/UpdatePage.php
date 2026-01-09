<?php

namespace App\Livewire\WasteTypes;

use App\Services\WasteTypeService;
use App\Models\WasteType;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UpdatePage extends Component
{
    public WasteType $wasteType;
    public $code = '';
    public $name = '';
    public $description = '';

    protected WasteTypeService $wasteTypeService;

    public function boot(WasteTypeService $wasteTypeService)
    {
        $this->wasteTypeService = $wasteTypeService;
    }

    public function mount(WasteType $wasteType)
    {
        $this->wasteType = $wasteType;
        $this->code = $wasteType->code;
        $this->name = $wasteType->name;
        $this->description = $wasteType->description;
    }

    public function rules()
    {
        return [
            'code' => ['required', 'string', 'max:255', Rule::unique('waste_types')->ignore($this->wasteType->id)],
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

    public function update()
    {
        $this->authorize('update', $this->wasteType);
        $this->validate();

        $data = [
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
        ];

        try {
            $this->wasteTypeService->updateWasteTypeInformation($this->wasteType, $data);

            session()->flash('success', __('Waste type has been successfully updated!'));

            return redirect()->route('waste-types.index');
        } catch (\Exception $e) {
            session()->flash('error', __('An error occurred while updating the waste type. Please try again.'));
        }
    }

    public function render()
    {
        $this->authorize('update', $this->wasteType);
        return view('livewire.waste-types.update-page');
    }
}