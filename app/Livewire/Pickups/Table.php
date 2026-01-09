<?php

namespace App\Livewire\Pickups;

use App\DataTable\DataTableFactory;
use App\Models\Pickup;
use App\Traits\Livewire\WithDataTable;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithDataTable, WithPagination;

    public $filterStatus = '';
    public $filterDriver = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';

    public function boot()
    {
        $this->deleteAction = 'deletePickup';
        $this->routeIdColumn = 'id';
        $this->setDataTableFactory($this->getDataTableConfig());
    }

    private function getDataTableConfig(): DataTableFactory
    {
        return DataTableFactory::make()
            ->model(Pickup::class)
            ->headers([
                [
                    'key' => 'id',
                    'label' => __('ID'),
                    'sortable' => true
                ],
                [
                    'key' => 'client.company_name',
                    'label' => __('Client'),
                    'sortable' => true,
                    "accessor" => true
                ],
                [
                    'key' => 'driver_name',
                    'label' => __('Driver'),
                    'sortable' => true,
                ],
                [
                    'key' => 'status_label',
                    'label' => __('Status'),
                    'sortable' => true
                ],
                [
                    'key' => 'waste_type_name',
                    'label' => __('Waste Type'),
                    'sortable' => false,
                    "accessor" => true
                ],
                [
                    'key' => 'scheduled_date',
                    'label' => __('Scheduled Date'),
                    'sortable' => true,
                    'type' => 'date'
                ],
            ])
            ->deleteAction('deletePickup')
            ->searchPlaceholder(__('Search pickups...'))
            ->emptyMessage(__('No pickups found'))
            ->searchQuery($this->search)
            ->sortColumn($this->sortColumn)
            ->sortDirection($this->sortDirection)
            ->showBulkActions(Auth::user()->canManage())
            ->showCreate(Auth::user()->can('create', Pickup::class))
            ->createRoute('pickups.create')
            ->editRoute('pickups.edit')
            ->viewRoute('pickups.view')
            ->bulkDeleteAction('bulkDelete');
    }

    public function rowsQuery()
    {
        $query = Pickup::query()
            ->with(['client', 'driver.user', 'wasteType', 'boxes']);

        if ($this->filterStatus !== '') {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterDriver !== '') {
            $query->where('driver_id', $this->filterDriver);
        }

        if ($this->filterDateFrom !== '') {
            $query->where('scheduled_date', '>=', $this->filterDateFrom);
        }

        if ($this->filterDateTo !== '') {
            $query->where('scheduled_date', '<=', $this->filterDateTo);
        }

        $dataTable = $this->getDataTableConfig();

        return $this->applySearchAndSort($query, [
            'certificate_number',
            'driver_note'
        ], $dataTable);
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery()->paginate($this->perPage);
    }

    public function updateStatus($id, $status)
    {
        $pickup = Pickup::findOrFail($id);
        $this->authorize('update', $pickup);

        try {
            $pickup->update(['status' => $status]);

            $this->dispatch('show-message', [
                'message' => __('Pickup status has been updated.'),
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('show-message', [
                'message' => __('Failed to update status.'),
                'type' => 'error'
            ]);
        }
    }

    public function resetFilters()
    {
        $this->filterStatus = '';
        $this->filterDriver = '';
        $this->filterDateFrom = '';
        $this->filterDateTo = '';
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterDriver()
    {
        $this->resetPage();
    }

    public function updatedFilterDateFrom()
    {
        $this->resetPage();
    }

    public function updatedFilterDateTo()
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->authorize('viewAny', Pickup::class);
        $dataTable = $this->getDataTableConfig()->toArray();
        $selectedRowsCount = $this->getSelectedRowsCountProperty();

        return view('livewire.pickups.table', [
            'dataTable' => $dataTable,
            'selectedRowsCount' => $selectedRowsCount,
        ]);
    }

    public function bulkDelete()
    {
        $query = Pickup::query();
        if ($this->selectAll) {
            $query = $this->rowsQuery();
        } else {
            $query->whereIn('id', $this->selectedRows);
        }

        $query->delete();

        $this->clearSelection();
        $this->dispatch('show-message', [
            'message' => __('Pickups have been successfully deleted.'),
            'type' => 'success'
        ]);
    }

    public function deletePickup($id)
    {
        $pickup = Pickup::findOrFail($id);
        $this->authorize('delete', $pickup);

        $pickup->delete();

        $this->dispatch('show-message', [
            'message' => __('Pickup has been successfully deleted.'),
            'type' => 'success'
        ]);
    }
}
