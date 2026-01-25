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
            ->select('pickups.*')
            ->leftJoin('clients', 'pickups.client_id', '=', 'clients.id')
            ->leftJoin('drivers', 'pickups.assigned_driver_id', '=', 'drivers.id')
            ->leftJoin('users as driver_users', 'drivers.user_id', '=', 'driver_users.id')
            ->leftJoin('waste_types', 'pickups.waste_type_id', '=', 'waste_types.id')
            ->with(['client', 'driver.user', 'wasteType', 'boxes']);

        if ($this->filterStatus !== '') {
            $query->where('pickups.status', $this->filterStatus);
        }

        if ($this->filterDriver !== '') {
            $query->where('pickups.assigned_driver_id', $this->filterDriver);
        }

        if ($this->filterDateFrom !== '') {
            $query->where('pickups.scheduled_date', '>=', $this->filterDateFrom);
        }

        if ($this->filterDateTo !== '') {
            $query->where('pickups.scheduled_date', '<=', $this->filterDateTo);
        }

        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';
            
            $query->where(function ($q) use ($searchTerm) {
                $q->where('pickups.certificate_number', 'ilike', $searchTerm)
                  ->orWhere('pickups.driver_note', 'ilike', $searchTerm)
                  ->orWhere('clients.company_name', 'ilike', $searchTerm)
                  ->orWhere('clients.vat_id', 'ilike', $searchTerm)
                  ->orWhere('clients.registered_street_name', 'ilike', $searchTerm)
                  ->orWhere('clients.registered_street_number', 'ilike', $searchTerm)
                  ->orWhere('clients.registered_city', 'ilike', $searchTerm)
                  ->orWhere('clients.registered_zip_code', 'ilike', $searchTerm)
                  ->orWhere('clients.registered_province', 'ilike', $searchTerm)
                  ->orWhere('clients.premises_street_name', 'ilike', $searchTerm)
                  ->orWhere('clients.premises_street_number', 'ilike', $searchTerm)
                  ->orWhere('clients.premises_city', 'ilike', $searchTerm)
                  ->orWhere('clients.premises_zip_code', 'ilike', $searchTerm)
                  ->orWhere('clients.premises_province', 'ilike', $searchTerm)
                  ->orWhere('driver_users.first_name', 'ilike', $searchTerm)
                  ->orWhere('driver_users.last_name', 'ilike', $searchTerm)
                  ->orWhere('waste_types.name', 'ilike', $searchTerm);
            });
        }

        if ($this->sortColumn && $this->sortDirection) {
            switch ($this->sortColumn) {
                case 'client.company_name':
                    $query->orderBy('clients.company_name', $this->sortDirection);
                    break;
                case 'driver_name':
                    $query->orderBy('driver_users.first_name', $this->sortDirection)
                          ->orderBy('driver_users.last_name', $this->sortDirection);
                    break;
                case 'waste_type_name':
                    $query->orderBy('waste_types.name', $this->sortDirection);
                    break;
                default:
                    if (in_array($this->sortColumn, ['id', 'scheduled_date', 'status', 'certificate_number'])) {
                        $query->orderBy('pickups.' . $this->sortColumn, $this->sortDirection);
                    }
                    break;
            }
        } else {
            $query->orderBy('pickups.scheduled_date', 'desc');
        }

        return $query;
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
            $ids = $this->rowsQuery()->pluck('pickups.id');
            $query->whereIn('id', $ids);
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