<div class="relative" x-data="{ open: @entangle('showDropdown') }">
    @if ($label)
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        {{-- Show selected item as a badge/pill when selected --}}
        @if ($selected && $selectedDisplay)
            <div class="block w-full px-4 py-2 text-sm border border-gray-300 rounded-lg bg-white {{ $disabled ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                <div class="flex items-center justify-between">
                    <span class="text-gray-900">{{ $selectedDisplay }}</span>
                    @if (!$disabled)
                        <button type="button"
                            wire:click="clearSelection"
                            class="ml-2 text-gray-400 hover:text-red-600 transition-colors duration-150 flex-shrink-0"
                            title="Remove selection">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        @else
            {{-- Show search input when no item is selected --}}
            <input type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="{{ $placeholder }}"
                @if ($disabled) disabled @endif
                class="block w-full px-4 py-2 pr-10 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-150 {{ $disabled ? 'bg-gray-100 cursor-not-allowed' : 'bg-white' }}"
                @click="open = true"
                autocomplete="off" />

            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                @if (filled($search) && !$disabled)
                    <button type="button"
                        wire:click="clearSelection"
                        @click.stop="open = false"
                        class="pointer-events-auto text-gray-400 hover:text-gray-600 transition-colors duration-150"
                        title="Clear search">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                @else
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                @endif
            </div>
        @endif

        <input type="hidden" name="{{ $name }}" value="{{ $selected }}" />
    </div>

    {{-- Results Dropdown --}}
    @if ($showDropdown && $results->isNotEmpty() && !$selected)
        <div x-show="open" @click.away="open = false"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-auto">
            <ul class="py-1">
                @foreach ($results as $result)
                    <li>
                        <button type="button" wire:click="selectItem({{ $result->{$valueField} }})"
                            class="w-full px-4 py-2 text-left text-sm hover:bg-emerald-50 transition-colors duration-150 flex items-center justify-between group">
                            <span class="text-gray-900">{{ $this->getDisplayValue($result) }}</span>
                            <svg class="w-4 h-4 text-emerald-700 opacity-0 group-hover:opacity-100 transition-opacity duration-150"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Empty State --}}
    @if ($showDropdown && $results->isEmpty() && strlen($search) >= 2 && !$selected)
        <div x-show="open" @click.away="open = false"
            class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg">
            <div class="px-4 py-3 text-sm text-gray-500 text-center">
                {{ __('No results found') }}
            </div>
        </div>
    @endif

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
