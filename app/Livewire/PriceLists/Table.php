<?php

namespace App\Livewire\PriceLists;

use App\DataTable\DataTableFactory;
use App\Models\PriceList;
use App\Traits\Livewire\WithDataTable;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithDataTable, WithPagination;

    public $filterIsActive = '';

    public function boot()
    {
        $this->deleteAction = 'deletePriceList';
        $this->routeIdColumn = 'id';
        $this->setDataTableFactory($this->getDataTableConfig());
    }

    private function getDataTableConfig(): DataTableFactory
    {
        return DataTableFactory::make()
            ->model(PriceList::class)
            ->headers([
                [
                    'key' => 'id',
                    'label' => __('ID'),
                    'sortable' => true
                ],
                [
                    'key' => 'name',
                    'label' => __('Name'),
                    'sortable' => true
                ],
                [
                    'key' => 'is_active',
                    'label' => __('Status'),
                    'sortable' => true,
                    'type' => 'boolean'
                ],
                [
                    'key' => 'created_at',
                    'label' => __('Created'),
                    'sortable' => true,
                    'type' => 'datetime'
                ],
            ])
            ->deleteAction('deletePriceList')
            ->searchPlaceholder(__('Search price lists...'))
            ->emptyMessage(__('No price lists found'))
            ->searchQuery($this->search)
            ->sortColumn($this->sortColumn)
            ->sortDirection($this->sortDirection)
            ->showBulkActions(Auth::user()->isAdmin())
            ->showCreate(Auth::user()->can('create', PriceList::class))
            ->createRoute('price-lists.create')
            ->editRoute('price-lists.edit')
            ->bulkDeleteAction('bulkDelete');
    }

    public function rowsQuery()
    {
        $query = PriceList::query()
            ->withCount(['items', 'clients']);

        if ($this->filterIsActive !== '') {
            $query->where('is_active', $this->filterIsActive);
        }

        $dataTable = $this->getDataTableConfig();

        return $this->applySearchAndSort($query, [
            'name',
            'description'
        ], $dataTable);
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery()->paginate($this->perPage);
    }

    public function toggleActive($id)
    {
        $priceList = PriceList::findOrFail($id);
        $this->authorize('update', $priceList);

        $priceList->update([
            'is_active' => !$priceList->is_active
        ]);

        $this->dispatch('show-message', [
            'message' => __('Price list status has been updated.'),
            'type' => 'success'
        ]);
    }

    public function duplicate($id)
    {
        $priceList = PriceList::with('items')->findOrFail($id);
        $this->authorize('create', PriceList::class);

        $newPriceList = $priceList->replicate();
        $newPriceList->name = $priceList->name . ' (Copy)';
        $newPriceList->is_active = false;
        $newPriceList->save();

        foreach ($priceList->items as $item) {
            $newItem = $item->replicate();
            $newItem->price_list_id = $newPriceList->id;
            $newItem->save();
        }

        $this->dispatch('show-message', [
            'message' => __('Price list has been duplicated successfully.'),
            'type' => 'success'
        ]);

        return redirect()->route('price-lists.edit', $newPriceList);
    }

    public function resetFilters()
    {
        $this->filterIsActive = '';
        $this->resetPage();
    }

    public function updatedFilterIsActive()
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->authorize('viewAny', PriceList::class);
        $dataTable = $this->getDataTableConfig()->toArray();
        $selectedRowsCount = $this->getSelectedRowsCountProperty();

        return view('livewire.price-lists.table', [
            'dataTable' => $dataTable,
            'selectedRowsCount' => $selectedRowsCount,
        ]);
    }

    public function bulkDelete()
    {
        $query = PriceList::query();
        if ($this->selectAll) {
            $query = $this->rowsQuery();
        } else {
            $query->whereIn('id', $this->selectedRows);
        }

        $query->delete();

        $this->clearSelection();
        $this->dispatch('show-message', [
            'message' => __('Price lists have been successfully deleted.'),
            'type' => 'success'
        ]);
    }

    public function deletePriceList($id)
    {
        $priceList = PriceList::findOrFail($id);
        $this->authorize('delete', $priceList);

        $priceList->delete();

        $this->dispatch('show-message', [
            'message' => __('Price list has been successfully deleted.'),
            'type' => 'success'
        ]);
    }
}