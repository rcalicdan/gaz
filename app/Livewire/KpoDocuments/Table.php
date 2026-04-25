<?php

namespace App\Livewire\KpoDocuments;

use App\DataTable\DataTableFactory;
use App\Models\KpoDocument;
use App\Traits\Livewire\WithDataTable;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithDataTable, WithPagination;

    public $filterEmailed = '';
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
            ->model(KpoDocument::class)
            ->headers([
                ['key' => 'id', 'label' => __('ID'), 'sortable' => true],
                ['key' => 'kpo_number', 'label' => __('KPO Number'), 'sortable' => true],
                ['key' => 'client.company_name', 'label' => __('Client'), 'sortable' => true, 'accessor' => true],
                ['key' => 'waste_code', 'label' => __('Waste Code'), 'sortable' => true],
                ['key' => 'quantity', 'label' => __('Quantity (kg)'), 'sortable' => true],
                ['key' => 'email_status_badge', 'label' => __('Email Status'), 'sortable' => false, 'type' => 'badge'],
                ['key' => 'created_at', 'label' => __('Created At'), 'sortable' => true, 'type' => 'date'],
            ])
            ->showSearch(true)
            ->showCreate(false) 
            ->createRoute('')
            ->viewRoute('kpo-documents.view')
            ->searchPlaceholder(__('Search by KPO number, waste code or client...'))
            ->emptyMessage(__('No KPO documents found'))
            ->searchQuery($this->search)
            ->sortColumn($this->sortColumn ?: 'created_at')
            ->sortDirection($this->sortDirection ?: 'desc');
    }

    public function rowsQuery()
    {
        $query = KpoDocument::query()
            ->select('kpo_documents.*')
            ->leftJoin('clients', 'kpo_documents.client_id', '=', 'clients.id')
            ->with(['client', 'pickup']);

        if ($this->filterEmailed !== '') {
            $query->where('kpo_documents.is_emailed', $this->filterEmailed === '1');
        }
        if ($this->filterDateFrom !== '') {
            $query->whereDate('kpo_documents.created_at', '>=', $this->filterDateFrom);
        }
        if ($this->filterDateTo !== '') {
            $query->whereDate('kpo_documents.created_at', '<=', $this->filterDateTo);
        }

        if ($this->search) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('kpo_documents.kpo_number', 'ilike', $searchTerm)
                    ->orWhere('kpo_documents.waste_code', 'ilike', $searchTerm)
                    ->orWhere('clients.company_name', 'ilike', $searchTerm);
            });
        }

        $sortColumn = $this->sortColumn ?: 'created_at';
        $sortDirection = $this->sortDirection ?: 'desc';

        switch ($sortColumn) {
            case 'client.company_name':
                $query->orderBy('clients.company_name', $sortDirection);
                break;
            default:
                if (\in_array($sortColumn, ['id', 'kpo_number', 'waste_code', 'quantity', 'created_at'])) {
                    $query->orderBy('kpo_documents.' . $sortColumn, $sortDirection);
                }
                break;
        }

        return $query;
    }

    public function resetFilters()
    {
        $this->filterEmailed = '';
        $this->filterDateFrom = '';
        $this->filterDateTo = '';
        $this->resetPage();
    }

    public function getRowsProperty()
    {
        $paginator = $this->rowsQuery()->paginate($this->perPage);

        $paginator->getCollection()->transform(function ($kpo) {
            $kpo->email_status_badge = [
                'text' => $kpo->is_emailed ? __('Sent') : __('Pending'),
                'class' => $kpo->is_emailed ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
            ];
            return $kpo;
        });

        return $paginator;
    }

    public function render()
    {
        return view('livewire.kpo-documents.table', [
            'dataTable' => $this->getDataTableConfig()->toArray(),
            'rows' => $this->rows,
        ]);
    }
}