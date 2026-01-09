<?php

namespace App\Livewire\Clients;

use App\DataTable\DataTableFactory;
use App\Models\Client;
use App\Traits\Livewire\WithDataTable;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithDataTable, WithPagination;

    public $filterProvince = '';
    public $filterCity = '';
    public $filterBrandCategory = '';

    public function boot()
    {
        $this->deleteAction = 'deleteClient';
        $this->routeIdColumn = 'id';
        $this->setDataTableFactory($this->getDataTableConfig());
    }

    private function getDataTableConfig(): DataTableFactory
    {
        return DataTableFactory::make()
            ->model(Client::class)
            ->headers([
                ['key' => 'id', 'label' => __('ID'), 'sortable' => true],
                ['key' => 'company_name', 'label' => __('Company Name'), 'sortable' => true],
                ['key' => 'city', 'label' => __('City'), 'sortable' => true],
                ['key' => 'email', 'label' => __('Email'), 'sortable' => true],
                ['key' => 'phone_number', 'label' => __('Phone'), 'sortable' => true],
                ['key' => 'created_at', 'label' => __('Created'), 'sortable' => true, 'type' => 'datetime'],
            ])
            ->deleteAction('deleteClient')
            ->searchPlaceholder(__('Search clients...'))
            ->emptyMessage(__('No clients found'))
            ->searchQuery($this->search)
            ->sortColumn($this->sortColumn)
            ->sortDirection($this->sortDirection)
            ->showBulkActions(Auth::user()->isAdmin())
            ->showCreate(Auth::user()->can('create', Client::class))
            ->createRoute('clients.create')
            ->editRoute('clients.edit')
            ->viewRoute('clients.view')
            ->bulkDeleteAction('bulkDelete');
    }

    public function rowsQuery()
    {
        $query = Client::query()->with('defaultWasteType');

        if ($this->filterProvince) {
            $query->where('province', $this->filterProvince);
        }

        if ($this->filterCity) {
            $query->where('city', $this->filterCity);
        }

        if ($this->filterBrandCategory) {
            $query->where('brand_category', $this->filterBrandCategory);
        }

        $dataTable = $this->getDataTableConfig();

        return $this->applySearchAndSort($query, [
            'company_name', 
            'vat_id', 
            'city', 
            'email', 
            'phone_number',
            'street_name',
            'zip_code'
        ], $dataTable);
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery()->paginate($this->perPage);
    }

    public function getProvincesProperty()
    {
        return Client::query()
            ->select('province')
            ->whereNotNull('province')
            ->where('province', '!=', '')
            ->distinct()
            ->orderBy('province')
            ->pluck('province');
    }

    public function getCitiesProperty()
    {
        $query = Client::query()
            ->select('city')
            ->whereNotNull('city')
            ->where('city', '!=', '');

        if ($this->filterProvince) {
            $query->where('province', $this->filterProvince);
        }

        return $query->distinct()
            ->orderBy('city')
            ->pluck('city');
    }

    public function getBrandCategoriesProperty()
    {
        return Client::query()
            ->select('brand_category')
            ->whereNotNull('brand_category')
            ->where('brand_category', '!=', '')
            ->distinct()
            ->orderBy('brand_category')
            ->pluck('brand_category');
    }

    public function resetFilters()
    {
        $this->filterProvince = '';
        $this->filterCity = '';
        $this->filterBrandCategory = '';
        $this->resetPage();
    }

    public function updatedFilterProvince()
    {
        $this->filterCity = ''; 
        $this->resetPage();
    }

    public function updatedFilterCity()
    {
        $this->resetPage();
    }

    public function updatedFilterBrandCategory()
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->authorize('viewAny', Client::class);
        $dataTable = $this->getDataTableConfig()->toArray();
        $selectedRowsCount = $this->getSelectedRowsCountProperty();

        return view('livewire.clients.table', [
            'dataTable' => $dataTable,
            'selectedRowsCount' => $selectedRowsCount,
            'provinces' => $this->provinces,
            'cities' => $this->cities,
            'brandCategories' => $this->brandCategories,
        ]);
    }

    public function bulkDelete()
    {
        $query = Client::query();
        if ($this->selectAll) {
            $query = $this->rowsQuery();
        } else {
            $query->whereIn('id', $this->selectedRows);
        }

        $query->delete();

        $this->clearSelection();
        $this->dispatch('show-message', [
            'message' => __('Clients have been successfully deleted.'),
            'type' => 'success'
        ]);
    }

    public function deleteClient($id)
    {
        $client = Client::findOrFail($id);
        $this->authorize('delete', $client);

        $client->delete();

        $this->dispatch('show-message', [
            'message' => __('Client has been successfully deleted.'),
            'type' => 'success'
        ]);
    }
}