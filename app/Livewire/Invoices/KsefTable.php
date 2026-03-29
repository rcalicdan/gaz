<?php

namespace App\Livewire\Invoices;

use App\DataTable\DataTableFactory;
use App\Models\Invoice;
use App\Traits\Livewire\WithDataTable;
use Livewire\Component;
use Livewire\WithPagination;

class KsefTable extends Component
{
    use WithDataTable, WithPagination;

    public $filterStatus = '';

    public $filterDateFrom = '';

    public $filterDateTo = '';

    public function boot()
    {
        $this->routeIdColumn = 'id';
        $this->setDataTableFactory($this->getDataTableConfig());
    }

    private function getDataTableConfig(): DataTableFactory
    {
        return DataTableFactory::make()
            ->model(Invoice::class)
            ->headers([
                ['key' => 'id', 'label' => __('ID'), 'sortable' => true],
                ['key' => 'invoice_number', 'label' => __('Invoice No.'), 'sortable' => true],
                ['key' => 'client.company_name', 'label' => __('Seller (Client)'), 'sortable' => true, 'accessor' => true],
                ['key' => 'ksef_status_label', 'label' => __('KSeF Status'), 'sortable' => false, 'type' => 'badge'],
                ['key' => 'ksef_reference_number', 'label' => __('KSeF Number / Ref'), 'sortable' => false],
                ['key' => 'issue_date', 'label' => __('Issued At'), 'sortable' => true, 'type' => 'date'],
            ])
            ->showSearch(true)
            ->showCreate(false)
            ->createRoute('')
            ->viewRoute('invoices.view')
            ->searchPlaceholder(__('Search by invoice or KSeF number...'))
            ->emptyMessage(__('No invoices found'))
            ->searchQuery($this->search)
            ->sortColumn($this->sortColumn ?: 'created_at')
            ->sortDirection($this->sortDirection ?: 'desc');
    }

    public function rowsQuery()
    {
        $query = Invoice::query()
            ->select('invoices.*')
            ->leftJoin('clients', 'invoices.client_id', '=', 'clients.id')
            ->with(['client', 'pickup']);

        if ($this->filterStatus !== '') {
            $query->where('ksef_status', $this->filterStatus);
        }
        if ($this->filterDateFrom !== '') {
            $query->where('issue_date', '>=', $this->filterDateFrom);
        }
        if ($this->filterDateTo !== '') {
            $query->where('issue_date', '<=', $this->filterDateTo);
        }

        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('invoices.invoice_number', 'ilike', $searchTerm)
                    ->orWhere('invoices.ksef_reference_number', 'ilike', $searchTerm);
            });
        }

        $sortColumn = $this->sortColumn ?: 'created_at';
        $sortDirection = $this->sortDirection ?: 'desc';

        switch ($sortColumn) {
            case 'client.company_name':
                $query->orderBy('clients.company_name', $sortDirection);
                break;
            default:
                if (\in_array($sortColumn, ['id', 'invoice_number', 'issue_date', 'created_at'])) {
                    $query->orderBy('invoices.' . $sortColumn, $sortDirection);
                }
                break;
        }

        return $query;
    }

    public function resetFilters()
    {
        $this->filterStatus = '';
        $this->filterDateFrom = '';
        $this->filterDateTo = '';
        $this->resetPage();
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery()->paginate($this->perPage);
    }

    public function render()
    {
        return view('livewire.invoices.ksef-table', [
            'dataTable' => $this->getDataTableConfig()->toArray(),
            'rows' => $this->rows,
        ]);
    }
}
