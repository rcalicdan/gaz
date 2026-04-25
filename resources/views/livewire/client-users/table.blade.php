<div>
    <x-flash-session />
    <x-partials.dashboard.content-header :title="__('App Users & Approvals')" />

    <div class="mb-4">
        <div class="inline-flex items-center gap-2 bg-white rounded-lg shadow-sm border border-gray-200 px-2 py-2">
            <button wire:click="$set('statusFilter', 'pending')"
                class="px-3 py-1.5 text-sm font-medium rounded transition-colors
                {{ $statusFilter === 'pending'
                    ? 'bg-amber-100 text-amber-700 ring-1 ring-amber-200'
                    : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fas fa-clock mr-1"></i> {{ __('Pending Approval') }}
            </button>
            <button wire:click="$set('statusFilter', 'active')"
                class="px-3 py-1.5 text-sm font-medium rounded transition-colors
                {{ $statusFilter === 'active'
                    ? 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200'
                    : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fas fa-check-circle mr-1"></i> {{ __('Active Users') }}
            </button>
            <button wire:click="$set('statusFilter', 'all')"
                class="px-3 py-1.5 text-sm font-medium rounded transition-colors
                {{ $statusFilter === 'all'
                    ? 'bg-blue-100 text-blue-700 ring-1 ring-blue-200'
                    : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fas fa-users mr-1"></i> {{ __('All') }}
            </button>
        </div>
    </div>

    <x-data-table :data="$this->rows" :headers="$dataTable['headers']" :showActions="$dataTable['showActions']" :showSearch="$dataTable['showSearch']" :showCreate="$dataTable['showCreate']"
        :createRoute="$dataTable['createRoute']" createButtonName="Create App User" :editRoute="$dataTable['editRoute']" :viewRoute="$dataTable['viewRoute']" :deleteAction="$dataTable['deleteAction']"
        :searchPlaceholder="$dataTable['searchPlaceholder']" :emptyMessage="$dataTable['emptyMessage']" :searchQuery="$search" :sortColumn="$sortColumn" :sortDirection="$sortDirection"
        :showBulkActions="$dataTable['showBulkActions']" :bulkDeleteAction="$dataTable['bulkDeleteAction']" :selectedRowsCount="$selectedRowsCount" :selectAll="$selectAll" :selectPage="$selectPage"
        :selectedRows="$selectedRows">

        <x-slot name="customColumns">
            @foreach ($this->rows as $row)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    {{-- Bulk checkbox --}}
                    @if ($dataTable['showBulkActions'])
                        <td class="px-3 lg:px-6 py-4">
                            <input type="checkbox" wire:model.live="selectedRows" value="{{ $row->id }}"
                                class="form-checkbox h-4 w-4 text-emerald-600 rounded border-gray-300">
                        </td>
                    @endif

                    <td class="px-3 lg:px-6 py-4 text-sm text-gray-900">{{ $row->id }}</td>
                    <td class="px-3 lg:px-6 py-4 text-sm text-gray-900">{{ $row->full_name }}</td>
                    <td class="px-3 lg:px-6 py-4 text-sm text-gray-900">
                        {{ $row->client?->company_name ?? '—' }}
                    </td>
                    <td class="px-3 lg:px-6 py-4 text-sm text-gray-900">{{ $row->email }}</td>

                    {{-- Status badge --}}
                    <td class="px-3 lg:px-6 py-4 text-sm">
                        @if ($row->active)
                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        @else
                            <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 animate-pulse">
                                Pending
                            </span>
                        @endif
                    </td>

                    <td class="px-3 lg:px-6 py-4 text-sm text-gray-900">
                        {{ $row->created_at?->format('d M Y') }}
                    </td>

                    {{-- Actions --}}
                    <td class="px-3 lg:px-6 py-4 text-sm text-right">
                        <div class="flex items-center justify-end gap-2">
                            @if (!$row->active)
                                <button wire:click="approveUser({{ $row->id }})"
                                    wire:confirm="{{ __('Approve this user?') }}"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-white bg-emerald-600 rounded-md hover:bg-emerald-700 shadow-sm">
                                    <i class="fas fa-check mr-1"></i> {{ __('Approve') }}
                                </button>
                                <button wire:click="declineUser({{ $row->id }})"
                                    wire:confirm="{{ __('Decline request?') }}"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100">
                                    <i class="fas fa-times mr-1"></i> {{ __('Decline') }}
                                </button>
                            @else
                                <x-utils.update-button :route="route($dataTable['editRoute'], $row->id)" />
                                <x-utils.delete-button wireClick="deleteUser({{ $row->id }})" />
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-slot>

        <x-slot name="customMobileColumns">
            @foreach ($this->rows as $row)
                <div class="border-b border-gray-200 p-4 hover:bg-gray-50 transition-colors duration-150">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="text-sm font-bold text-gray-900">{{ $row->full_name }}</span>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $row->email }}</div>
                        </div>
                        <div>
                            @if ($row->active)
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                            @else
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 animate-pulse">Pending</span>
                            @endif
                        </div>
                    </div>

                    <div class="text-sm text-gray-600 mb-3">
                        <i class="fas fa-building mr-1"></i>
                        @if ($row->client)
                            {{ $row->client->company_name }}
                        @else
                            <span class="text-red-500 italic">No Company Linked</span>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-2 pt-3 border-t border-gray-100">
                        @if (!$row->active)
                            <button wire:click="approveUser({{ $row->id }})"
                                wire:confirm="{{ __('Approve this user?') }}"
                                class="flex-1 flex items-center justify-center px-3 py-2 text-xs font-bold text-white bg-emerald-600 rounded-md hover:bg-emerald-700 shadow-sm">
                                <i class="fas fa-check mr-1.5"></i> {{ __('Approve') }}
                            </button>
                            <button wire:click="declineUser({{ $row->id }})"
                                wire:confirm="{{ __('Decline request?') }}"
                                class="flex-1 flex items-center justify-center px-3 py-2 text-xs font-bold text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 shadow-sm">
                                <i class="fas fa-times mr-1.5"></i> {{ __('Decline') }}
                            </button>
                        @else
                            <a href="{{ route($dataTable['editRoute'], $row->id) }}"
                                class="flex-1 flex items-center justify-center px-3 py-2 text-xs font-medium text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-md">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <button wire:click="deleteUser({{ $row->id }})"
                                wire:confirm="{{ __('Delete user?') }}"
                                class="flex-1 flex items-center justify-center px-3 py-2 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-md">
                                <i class="fas fa-trash mr-1"></i> Delete
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </x-slot>

    </x-data-table>
</div>