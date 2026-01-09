<?php

namespace App\Livewire\Components;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class SearchableSelect extends Component
{
    public $modelClass;
    public $selected = null;
    public $search = '';
    public $placeholder = 'Search...';
    public $displayField = 'name';
    public $valueField = 'id';
    public $searchFields = ['name'];
    public $withRelations = [];
    public $additionalFilters = [];
    public $name;
    public $label;
    public $required = false;
    public $showDropdown = false;
    public $disabled = false;
    public $selectedDisplay = ''; 

    protected $listeners = ['resetSearchable'];

    public function mount(
        string $modelClass,
        ?int $selected = null,
        string $name = '',
        string $label = '',
        string $placeholder = 'Search...',
        string $displayField = 'name',
        string $valueField = 'id',
        array $searchFields = ['name'],
        array $withRelations = [],
        array $additionalFilters = [],
        bool $required = false,
        bool $disabled = false
    ) {
        $this->modelClass = $modelClass;
        $this->selected = $selected;
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->displayField = $displayField;
        $this->valueField = $valueField;
        $this->searchFields = $searchFields;
        $this->withRelations = $withRelations;
        $this->additionalFilters = $additionalFilters;
        $this->required = $required;
        $this->disabled = $disabled;

        if ($this->selected) {
            $selectedModel = $this->modelClass::find($this->selected);
            if ($selectedModel) {
                $this->selectedDisplay = $this->getDisplayValue($selectedModel);
            }
        }
    }

    public function updatedSearch()
    {
        if (!$this->disabled && !$this->selected) {
            $this->showDropdown = !empty($this->search);
        }
    }

    public function selectItem($id)
    {
        if ($this->disabled) return;

        $this->selected = $id;
        $model = $this->modelClass::find($id);
        
        if ($model) {
            $this->selectedDisplay = $this->getDisplayValue($model);
            $this->search = ''; 
        }
        
        $this->showDropdown = false;
        $this->dispatch('itemSelected', [
            'name' => $this->name,
            'value' => $id
        ]);
    }

    public function clearSelection()
    {
        if ($this->disabled) return;

        $this->selected = null;
        $this->selectedDisplay = '';
        $this->search = '';
        $this->showDropdown = false;
        
        $this->dispatch('itemSelected', [
            'name' => $this->name,
            'value' => null
        ]);
    }

    public function resetSearchable()
    {
        $this->clearSelection();
    }

    public function getResultsProperty()
    {
        if ($this->selected || empty($this->search)) {
            return collect();
        }

        $query = $this->modelClass::query();

        if (!empty($this->withRelations)) {
            $query->with($this->withRelations);
        }

        $query->where(function (Builder $q) {
            foreach ($this->searchFields as $field) {
                if (str_contains($field, '.')) {
                    $parts = explode('.', $field);
                    $relation = $parts[0];
                    $column = $parts[1];
                    $q->orWhereHas($relation, function ($query) use ($column) {
                        $query->where($column, 'ilike', '%' . $this->search . '%');
                    });
                } else {
                    $q->orWhere($field, 'ilike', '%' . $this->search . '%');
                }
            }
        });

        foreach ($this->additionalFilters as $field => $value) {
            $query->where($field, $value);
        }

        return $query->limit(10)->get();
    }

    protected function getDisplayValue($model): string
    {
        if (str_contains($this->displayField, '.')) {
            $parts = explode('.', $this->displayField);
            $value = $model;
            foreach ($parts as $part) {
                $value = $value->{$part} ?? '';
            }
            return (string) $value;
        }

        return (string) ($model->{$this->displayField} ?? '');
    }

    public function render()
    {
        return view('livewire.components.searchable-select', [
            'results' => $this->results
        ]);
    }
}