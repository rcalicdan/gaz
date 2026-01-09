<div>
    <x-flash-session />
    <x-partials.dashboard.content-header :title="__('Pickups Management')" />

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
                    <select wire:model.live="filterStatus"
                        class="appearance-none pl-3 pr-8 py-1.5 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-150 hover:border-gray-400 cursor-pointer min-w-[140px]">
                        <option value="">{{ __('All Status') }}</option>
                        @foreach (\App\Enums\PickupStatus::cases() as $status)
                            <option value="{{ $status->value }}">{{ $status->label() }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </div>
                </div>

                <div class="relative">
                    <input type="date" wire:model.live="filterDateFrom"
                        class="pl-3 pr-3 py-1.5 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-150 hover:border-gray-400"
                        placeholder="{{ __('From Date') }}">
                </div>

                <div class="relative">
                    <input type="date" wire:model.live="filterDateTo"
                        class="pl-3 pr-3 py-1.5 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-150 hover:border-gray-400"
                        placeholder="{{ __('To Date') }}">
                </div>

                @if ($filterStatus !== '' || $filterDateFrom !== '' || $filterDateTo !== '')
                    <div class="flex items-center gap-2 ml-auto">
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                            {{ collect([$filterStatus, $filterDateFrom, $filterDateTo])->filter()->count() }}
                            {{ __('active') }}
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

            @if ($filterStatus !== '' || $filterDateFrom !== '' || $filterDateTo !== '')
                <div class="flex flex-wrap gap-1.5 mt-2.5 pt-2.5 border-t border-gray-100">
                    @if ($filterStatus !== '')
                        <span
                            class="inline-flex items-center gap-1 px-2 py-0.5 bg-gradient-to-r from-indigo-50 to-indigo-100 text-indigo-700 rounded text-xs font-medium border border-indigo-200">
                            <span class="text-indigo-400">📊</span>
                            {{ \App\Enums\PickupStatus::from($filterStatus)->label() }}
                            <button wire:click="$set('filterStatus', '')" class="hover:text-emerald-900 ml-0.5">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </span>
                    @endif

                    @if ($filterDateFrom !== '')
                        <span
                            class="inline-flex items-center gap-1 px-2 py-0.5 bg-white text-emerald-700 rounded text-xs font-medium border border-emerald-200">
                            <span class="text-emerald-400">📅</span>
                            {{ __('From') }}: {{ $filterDateFrom }}
                            <button wire:click="$set('filterDateFrom', '')" class="hover:text-emerald-900 ml-0.5">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </span>
                    @endif

                    @if ($filterDateTo !== '')
                        <span
                            class="inline-flex items-center gap-1 px-2 py-0.5 bg-white text-emerald-700 rounded text-xs font-medium border border-emerald-200">
                            <span class="text-emerald-400">📅</span>
                            {{ __('To') }}: {{ $filterDateTo }}
                            <button wire:click="$set('filterDateTo', '')" class="hover:text-emerald-900 ml-0.5">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <x-data-table :data="$this->rows" :headers="$dataTable['headers']" :showActions="true" :showSearch="$dataTable['showSearch']" :showCreate="$dataTable['showCreate']"
            :createRoute="$dataTable['createRoute']" :createButtonName="$dataTable['createButtonName']" :editRoute="$dataTable['editRoute']" :viewRoute="$dataTable['viewRoute']" :deleteAction="$dataTable['deleteAction']"
            :searchPlaceholder="$dataTable['searchPlaceholder']" :emptyMessage="$dataTable['emptyMessage']" :searchQuery="$search" :sortColumn="$sortColumn" :sortDirection="$sortDirection"
            :showBulkActions="$dataTable['showBulkActions']" :bulkDeleteAction="$dataTable['bulkDeleteAction']" :selectedRowsCount="$selectedRowsCount" :selectAll="$selectAll" :selectPage="$selectPage"
            :selectedRows="$selectedRows">
        </x-data-table>
    </div>
</div>
