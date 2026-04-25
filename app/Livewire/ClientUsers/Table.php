<?php

namespace App\Livewire\ClientUsers;

use App\DataTable\DataTableFactory;
use App\Models\User;
use App\Enums\UserRole;
use App\Traits\Livewire\WithDataTable;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithDataTable, WithPagination;

    public $statusFilter = 'pending';

    public function boot()
    {
        $this->deleteAction = 'deleteUser';
        $this->routeIdColumn = 'id';
        $this->setDataTableFactory($this->getDataTableConfig());
    }

    private function getDataTableConfig(): DataTableFactory
    {
        return DataTableFactory::make()
            ->model(User::class)
            ->headers([
                ['key' => 'id', 'label' => __('ID'), 'sortable' => true],
                ['key' => 'full_name', 'label' => __('Name'), 'sortable' => true, 'accessor' => true, 'search_columns' => ['first_name', 'last_name'], 'sort_columns' => ['first_name', 'last_name']],
                ['key' => 'client.company_name', 'label' => __('Company'), 'sortable' => true, 'accessor' => true],
                ['key' => 'email', 'label' => __('Email'), 'sortable' => true],
                ['key' => 'active', 'label' => __('Status'), 'sortable' => true],
                ['key' => 'created_at', 'label' => __('Registered'), 'sortable' => true, 'type' => 'datetime'],
            ])
            ->deleteAction('deleteUser')
            ->searchPlaceholder(__('Search app users or companies...'))
            ->emptyMessage(__('No app users found'))
            ->searchQuery($this->search)
            ->sortColumn($this->sortColumn)
            ->sortDirection($this->sortDirection)
            ->showBulkActions(Auth::user()->isAdmin())
            ->showCreate(Auth::user()->can('create', User::class))
            ->createRoute('client-users.create')
            ->editRoute('client-users.edit')
            ->bulkDeleteAction('bulkDelete');
    }

    public function rowsQuery()
    {
        $query = User::with('client')->where('role', UserRole::CLIENT->value);

        if ($this->statusFilter === 'pending') {
            $query->where('active', false);
        } elseif ($this->statusFilter === 'active') {
            $query->where('active', true);
        }

        $dataTable = $this->getDataTableConfig();

        return $this->applySearchAndSort($query, ['first_name', 'last_name', 'email'], $dataTable);
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery()->paginate($this->perPage);
    }

    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $user->activate();

        $this->dispatch('show-message', [
            'message' => __('User has been approved and can now access the app.'),
            'type' => 'success'
        ]);
    }

    public function declineUser($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);

        $user->delete();

        $this->dispatch('show-message', [
            'message' => __('Registration request has been declined and removed.'),
            'type' => 'success'
        ]);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);

        $user->delete();

        $this->dispatch('show-message', [
            'message' => __('App user deleted.'),
            'type' => 'success'
        ]);
    }

    public function render()
    {
        $this->authorize('viewAny', User::class);
        $dataTable = $this->getDataTableConfig()->toArray();
        $selectedRowsCount = $this->getSelectedRowsCountProperty();

        return view('livewire.client-users.table', [
            'dataTable' => $dataTable,
            'selectedRowsCount' => $selectedRowsCount,
        ]);
    }
}