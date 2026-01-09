<?php

namespace App\Livewire\WasteTypes;

use App\DataTable\DataTableFactory;
use App\Models\WasteType;
use App\Traits\Livewire\WithDataTable;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithDataTable, WithPagination;

    public function boot()
    {
        $this->deleteAction = 'deleteWasteType';
        $this->routeIdColumn = 'id';
        $this->setDataTableFactory($this->getDataTableConfig());
    }

    private function getDataTableConfig(): DataTableFactory
    {
        return DataTableFactory::make()
            ->model(WasteType::class)
            ->headers([
                [
                    'key' => 'id',
                    'label' => __('ID'),
                    'sortable' => true
                ],
                [
                    'key' => 'code',
                    'label' => __('Code'),
                    'sortable' => true
                ],
                [
                    'key' => 'name',
                    'label' => __('Name'),
                    'sortable' => true
                ],
                [
                    'key' => 'description',
                    'label' => __('Description'),
                    'sortable' => true
                ],
                [
                    'key' => 'created_at',
                    'label' => __('Created'),
                    'sortable' => true,
                    'type' => 'datetime'
                ],
            ])
            ->deleteAction('deleteWasteType')
            ->searchPlaceholder(__('Search waste types...'))
            ->emptyMessage(__('No waste types found'))
            ->searchQuery($this->search)
            ->sortColumn($this->sortColumn)
            ->sortDirection($this->sortDirection)
            ->showBulkActions(Auth::user()->isAdmin())
            ->showCreate(Auth::user()->can('create', WasteType::class))
            ->createRoute('waste-types.create')
            ->editRoute('waste-types.edit')
            ->bulkDeleteAction('bulkDelete');
    }

    public function rowsQuery()
    {
        $query = WasteType::query();

        $dataTable = $this->getDataTableConfig();

        return $this->applySearchAndSort($query, ['code', 'name', 'description'], $dataTable);
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery()->paginate($this->perPage);
    }

    public function render()
    {
        $this->authorize('viewAny', WasteType::class);
        $dataTable = $this->getDataTableConfig()->toArray();
        $selectedRowsCount = $this->getSelectedRowsCountProperty();

        return view('livewire.waste-types.table', [
            'dataTable' => $dataTable,
            'selectedRowsCount' => $selectedRowsCount,
        ]);
    }

    public function bulkDelete()
    {
        $query = WasteType::query();
        if ($this->selectAll) {
            $query = $this->rowsQuery();
        } else {
            $query->whereIn('id', $this->selectedRows);
        }

        $query->delete();

        $this->clearSelection();
        $this->dispatch('show-message', [
            'message' => __('Waste types have been successfully deleted.'),
            'type' => 'success'
        ]);
    }

    public function deleteWasteType($id)
    {
        $wasteType = WasteType::findOrFail($id);
        $this->authorize('delete', $wasteType);

        $wasteType->delete();

        $this->dispatch('show-message', [
            'message' => __('Waste type has been successfully deleted.'),
            'type' => 'success'
        ]);
    }
}