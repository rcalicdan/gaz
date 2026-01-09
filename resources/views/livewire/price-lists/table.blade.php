<div>
    <x-flash-session />
    <x-partials.dashboard.content-header :title="__('Price Lists Management')" />

    <div class="mb-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3">
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2 text-sm font-medium text-gray-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                    <span>{{ __('Filters') }}</span>
                </div>

                <div class="relative">
                    <select wire:model.live="filterIsActive"
                        class="appearance-none pl-3 pr-8 py-1.5 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-150 hover:border-gray-400 cursor-pointer min-w-[140px]">
                        <option value="">{{ __('All Status') }}</option>
                        <option value="1">{{ __('Active') }}</option>
                        <option value="0">{{ __('Inactive') }}</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                @if ($filterIsActive !== '')
                    <div class="flex items-center gap-2 ml-auto">
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                            1 {{ __('active') }}
                        </span>
                        <button wire:click="resetFilters" type="button"
                            class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-md transition-colors duration-150">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            {{ __('Clear') }}
                        </button>
                    </div>
                @endif
            </div>

            @if ($filterIsActive !== '')
                <div class="flex flex-wrap gap-1.5 mt-2.5 pt-2.5 border-t border-gray-100">
                    <span
                        class="inline-flex items-center gap-1 px-2 py-0.5 bg-gradient-to-r from-indigo-50 to-indigo-100 text-indigo-700 rounded text-xs font-medium border border-indigo-200">
                        <span class="text-indigo-400">📊</span>
                        {{ $filterIsActive == '1' ? __('Active') : __('Inactive') }}
                        <button wire:click="$set('filterIsActive', '')" class="hover:text-emerald-900 ml-0.5">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </span>
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <x-data-table :data="$this->rows" :headers="$dataTable['headers']" :showActions="true"
            :showSearch="$dataTable['showSearch']" :showCreate="$dataTable['showCreate']"
            :createRoute="$dataTable['createRoute']" :createButtonName="$dataTable['createButtonName']"
            :editRoute="$dataTable['editRoute']" :viewRoute="$dataTable['viewRoute']"
            :deleteAction="$dataTable['deleteAction']" :searchPlaceholder="$dataTable['searchPlaceholder']"
            :emptyMessage="$dataTable['emptyMessage']" :searchQuery="$search" :sortColumn="$sortColumn"
            :sortDirection="$sortDirection" :showBulkActions="$dataTable['showBulkActions']"
            :bulkDeleteAction="$dataTable['bulkDeleteAction']" :selectedRowsCount="$selectedRowsCount"
            :selectAll="$selectAll" :selectPage="$selectPage" :selectedRows="$selectedRows">

            <x-slot name="customColumns">
                @foreach ($this->rows as $row)
                    <tr class="hover:bg-gray-50 transition-colors duration-150"
                        wire:key="row-{{ $row->id }}">
                        @if ($dataTable['showBulkActions'])
                            <td class="px-6 py-4">
                                <input type="checkbox" wire:model.live="selectedRows"
                                    value="{{ $row->id }}"
                                    class="h-4 w-4 text-emerald-700 rounded border-gray-300 focus:ring-emerald-600">
                            </td>
                        @endif

                        <td class="px-6 py-4 text-sm text-gray-900">{{ $row->id }}</td>

                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $row->name }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ Str::limit($row->description, 50) ?: '-' }}
                        </td>

                        <td class="px-6 py-4">
                            <button wire:click="toggleActive({{ $row->id }})"
                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium transition-colors duration-150
                                {{ $row->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                {{ $row->is_active ? __('Active') : __('Inactive') }}
                            </button>
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-900">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                {{ $row->items_count }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-900">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                                {{ $row->clients_count }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $row->created_at->format('M d, Y') }}
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="duplicate({{ $row->id }})"
                                    wire:confirm="Are you sure you want to duplicate this price list?"
                                    class="text-emerald-600 hover:text-emerald-900 transition-colors duration-150"
                                    title="{{ __('Duplicate') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </button>

                                <a href="{{ route($dataTable['editRoute'], $row->id) }}"
                                    class="text-emerald-700 hover:text-emerald-900 transition-colors duration-150"
                                    title="{{ __('Edit') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </a>

                                <button wire:click="deletePriceList({{ $row->id }})"
                                    wire:confirm="Are you sure you want to delete this price list?"
                                    class="text-red-600 hover:text-red-900 transition-colors duration-150"
                                    title="{{ __('Delete') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-slot>
        </x-data-table>
    </div>
</div>
