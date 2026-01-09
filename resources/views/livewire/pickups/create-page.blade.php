<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-semibold text-gray-900">{{ __('Create New Pickup') }}</h2>
        </div>

        <form wire:submit.prevent="save" class="p-6 space-y-8">
            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                {{ __('There were errors with your submission') }}
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Basic Information --}}
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                    {{ __('Pickup Information') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Client Searchable Select --}}
                    <div>
                        <livewire:components.searchable-select :modelClass="\App\Models\Client::class" :selected="$client_id" name="client_id"
                            label="{{ __('Client') }}" placeholder="{{ __('Search client by name, VAT, or city...') }}"
                            displayField="company_name" :searchFields="['company_name', 'vat_id', 'city', 'contact_person']" :required="true" :key="'client-select'" />
                    </div>

                    {{-- Driver Searchable Select --}}
                    <div>
                        <livewire:components.searchable-select :modelClass="\App\Models\Driver::class" :selected="$assigned_driver_id"
                            name="assigned_driver_id" label="{{ __('Driver') }}"
                            placeholder="{{ __('Search driver...') }}" displayField="user.full_name" :searchFields="['user.first_name', 'user.last_name', 'user.email', 'license_number']"
                            :required="false" :key="'driver-select'" />
                    </div>

                    {{-- Waste Type Searchable Select --}}
                    <div>
                        <livewire:components.searchable-select :modelClass="\App\Models\WasteType::class" :selected="$waste_type_id"
                            name="waste_type_id" label="{{ __('Waste Type') }}"
                            placeholder="{{ __('Search waste type...') }}" displayField="name" :searchFields="['name', 'code']"
                            :required="true" :key="'waste-type-select'" />
                    </div>

                    <x-forms.field label="{{ __('Scheduled Date') }}" name="scheduled_date" required>
                        <x-forms.input type="date" name="scheduled_date" wire:model="scheduled_date" required />
                    </x-forms.field>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-forms.field label="{{ __('Status') }}" name="status" required>
                        <x-forms.select name="status" wire:model="status" :options="\App\Enums\PickupStatus::options()" required />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Sequence Order') }}" name="sequence_order">
                        <x-forms.input type="number" name="sequence_order" wire:model="sequence_order"
                            placeholder="{{ __('Optional') }}" />
                    </x-forms.field>
                </div>
            </div>

            {{-- Pickup Details --}}
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                    {{ __('Pickup Details') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <x-forms.field label="{{ __('Waste Quantity') }}" name="waste_quantity"
                        help="{{ __('In kg or tons') }}">
                        <x-forms.input type="number" step="0.01" name="waste_quantity" wire:model="waste_quantity"
                            placeholder="{{ __('0.00') }}" />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Applied Price Rate') }}" name="applied_price_rate"
                        help="{{ __('Override price') }}">
                        <x-forms.input type="number" step="0.01" name="applied_price_rate"
                            wire:model="applied_price_rate" placeholder="{{ __('0.00') }}" />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Certificate Number') }}" name="certificate_number">
                        <x-forms.input type="text" name="certificate_number" wire:model="certificate_number"
                            placeholder="{{ __('Certificate number') }}" />
                    </x-forms.field>
                </div>

                <x-forms.field label="{{ __('Driver Notes') }}" name="driver_note">
                    <x-forms.textarea name="driver_note" wire:model="driver_note"
                        placeholder="{{ __('Enter any notes for the driver (optional)') }}" rows="3" />
                </x-forms.field>
            </div>

            {{-- Boxes --}}
            <div class="space-y-6">
                <div class="flex items-center justify-between border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __('Boxes') }}
                    </h3>
                    <button type="button" wire:click="addBox"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-700 text-white text-sm font-medium rounded-lg hover:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-600 transition-colors duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ __('Add Box') }}
                    </button>
                </div>

                @if (count($boxes) === 0)
                    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">{{ __('No boxes added yet') }}</p>
                        <button type="button" wire:click="addBox"
                            class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-white text-emerald-900 text-sm font-medium rounded-lg border border-emerald-900 hover:bg-gray-50 transition-colors duration-150">
                            {{ __('Add First Box') }}
                        </button>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($boxes as $index => $box)
                            <div wire:key="box-{{ $index }}"
                                class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-sm font-semibold text-gray-700">
                                        {{ __('Box') }} #{{ $index + 1 }}
                                    </h4>
                                    <button type="button" wire:click="removeBox({{ $index }})"
                                        class="text-red-600 hover:text-red-800 transition-colors duration-150">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <x-forms.field label="{{ __('Box Number') }}"
                                        name="boxes.{{ $index }}.box_number" required>
                                        <x-forms.input type="text" name="boxes.{{ $index }}.box_number"
                                            wire:model="boxes.{{ $index }}.box_number"
                                            placeholder="{{ __('Enter box number') }}" required />
                                    </x-forms.field>

                                    <x-forms.field label="{{ __('Note') }}"
                                        name="boxes.{{ $index }}.note">
                                        <x-forms.input type="text" name="boxes.{{ $index }}.note"
                                            wire:model="boxes.{{ $index }}.note"
                                            placeholder="{{ __('Optional note') }}" />
                                    </x-forms.field>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <x-utils.link-button href="{{ route('pickups.index') }}" buttonText="{{ __('Cancel') }}" />
                <x-utils.submit-button wire-target="save" buttonText="{{ __('Create Pickup') }}"
                    bgColor="bg-emerald-700" hoverColor="hover:bg-emerald-900" focusRing="focus:ring-emerald-600" />
            </div>
        </form>
    </div>
</div>
