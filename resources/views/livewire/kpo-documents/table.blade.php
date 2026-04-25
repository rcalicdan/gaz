<div class="flex flex-col min-h-full">
    <x-flash-session />
    <x-partials.dashboard.content-header :title="__('KPO Documents Monitoring')" />

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

                {{-- Email Status Filter --}}
                <div class="relative">
                    <select wire:model.live="filterEmailed"
                        class="appearance-none pl-3 pr-8 py-1.5 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-150 hover:border-gray-400 cursor-pointer min-w-[140px]">
                        <option value="">{{ __('All Email Statuses') }}</option>
                        <option value="1">{{ __('Sent') }}</option>
                        <option value="0">{{ __('Pending / Failed') }}</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                {{-- Date From --}}
                <div class="relative">
                    <input type="date" wire:model.live="filterDateFrom"
                        class="pl-3 pr-3 py-1.5 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-150 hover:border-gray-400"
                        placeholder="{{ __('From Date') }}">
                </div>

                {{-- Date To --}}
                <div class="relative">
                    <input type="date" wire:model.live="filterDateTo"
                        class="pl-3 pr-3 py-1.5 text-sm border border-gray-300 rounded-md bg-white text-gray-900 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-150 hover:border-gray-400"
                        placeholder="{{ __('To Date') }}">
                </div>

                @php
                    $activeFiltersCount = collect([$filterEmailed, $filterDateFrom, $filterDateTo])->filter(fn($val) => $val !== '')->count();
                @endphp

                @if ($activeFiltersCount > 0)
                    <div class="flex items-center gap-2 ml-auto">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                            {{ $activeFiltersCount }} {{ __('active') }}
                        </span>
                        <button wire:click="resetFilters" type="button"
                            class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-md transition-colors duration-150">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            {{ __('Clear') }}
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="flex-1">
        <x-data-table 
            :data="$this->rows" 
            :headers="$dataTable['headers']" 
            :showActions="true" 
            :showSearch="true" 
            :showCreate="false"
            :createRoute="''" 
            :editRoute="''" 
            :viewRoute="$dataTable['viewRoute']" 
            :deleteAction="''" 
            :searchPlaceholder="$dataTable['searchPlaceholder']"
            :emptyMessage="$dataTable['emptyMessage']" 
            :searchQuery="$search" 
            :sortColumn="$sortColumn" 
            :sortDirection="$sortDirection">
        </x-data-table>
    </div>
</div>