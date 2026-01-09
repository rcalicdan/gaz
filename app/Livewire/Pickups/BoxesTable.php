<?php

namespace App\Livewire\Pickups;

use App\DataTable\DataTableFactory;
use App\Models\Pickup;
use App\Models\PickupBox;
use App\Traits\Livewire\WithDataTable;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class BoxesTable extends Component
{
    use WithDataTable, WithPagination;

    public Pickup $pickup;

    public function boot()
    {
        $this->deleteAction = 'deleteBox';
        $this->routeIdColumn = 'id';
        $this->setDataTableFactory($this->getDataTableConfig());
    }

    public function mount(Pickup $pickup)
    {
        $this->pickup = $pickup;
    }

    private function getDataTableConfig(): DataTableFactory
    {
        $pickupId = $this->pickup->id;

        return DataTableFactory::make()
            ->model(PickupBox::class)
            ->headers([
                [
                    'key' => 'box_number',
                    'label' => __('Box Number'),
                    'sortable' => true
                ],
                [
                    'key' => 'note',
                    'label' => __('Note'),
                    'sortable' => false
                ],
                [
                    'key' => 'created_at',
                    'label' => __('Added On'),
                    'sortable' => true,
                    'type' => 'datetime'
                ],
            ])
            ->searchPlaceholder(__('Search boxes...'))
            ->emptyMessage(__('No boxes found for this pickup'))
            ->showSearch(true)
            ->createRoute("$pickupId/boxes/create")
            ->showCreate(true)
            ->searchQuery($this->search)
            ->sortColumn($this->sortColumn ?? 'box_number')
            ->sortDirection($this->sortDirection ?? 'asc')
            ->showActions(true)
            ->deleteAction('deleteBox')
            ->editRoute('boxes.edit')
            ->viewRoute('boxes.show');
    }

    public function rowsQuery()
    {
        $query = PickupBox::query()
            ->where('pickup_id', $this->pickup->id);

        $dataTable = $this->getDataTableConfig();

        return $this->applySearchAndSort($query, [
            'box_number',
            'note'
        ], $dataTable);
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery()->paginate($this->perPage ?? 10);
    }

    public function deleteBox($id)
    {
        $box = PickupBox::findOrFail($id);
        $box->delete();

        $this->dispatch('show-message', [
            'message' => __('Box has been successfully deleted.'),
            'type' => 'success'
        ]);
    }

    public function render()
    {
        return view('livewire.pickups.boxes-table', [
            'dataTable' => $this->getDataTableConfig()->toArray(),
            'rows' => $this->rows
        ]);
    }
}
