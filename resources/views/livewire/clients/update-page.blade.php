<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 my-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <form wire:submit.prevent="update" class="p-6 space-y-8">
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                    {{ __('Company Information') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-forms.field label="{{ __('Company Name') }}" name="company_name" required>
                        <x-forms.input name="company_name" wire:model="company_name"
                            placeholder="{{ __('Enter company name') }}" required :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4\'></path>'" />
                    </x-forms.field>

                    <x-forms.field label="{{ __('VAT ID') }}" name="vat_id">
                        <x-forms.input name="vat_id" wire:model="vat_id" placeholder="{{ __('Enter VAT ID') }}"
                            :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'></path>'" />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Contract Number') }}" name="contract_number">
                        <x-forms.input name="contract_number" wire:model="contract_number"
                            placeholder="{{ __('Enter contract number') }}" :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'></path>'" />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Contract Signed Date') }}" name="contract_signed_date">
                        <x-forms.input type="date" name="contract_signed_date" wire:model="contract_signed_date"
                            :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z\'></path>'" />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Brand Category') }}" name="brand_category">
                        <x-forms.input name="brand_category" wire:model="brand_category"
                            placeholder="{{ __('Enter brand category') }}" :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z\'></path>'" />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Default Waste Type') }}" name="default_waste_type_id">
                        <x-forms.select name="default_waste_type_id" wire:model="default_waste_type_id"
                            placeholder="{{ __('Select waste type') }}" :options="$wasteTypes->pluck('name', 'id')->toArray()" />
                    </x-forms.field>
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __('Registered Address') }}
                    </h3>
                </div>
                <p class="text-sm text-gray-600 -mt-2 ml-14">{{ __('Official business registration address') }}</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <x-forms.field class="md:col-span-2" label="{{ __('Street Name') }}" name="registered_street_name"
                        required>
                        <x-forms.input name="registered_street_name" wire:model="registered_street_name"
                            placeholder="{{ __('Enter street name') }}" required />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Street Number') }}" name="registered_street_number">
                        <x-forms.input name="registered_street_number" wire:model="registered_street_number"
                            placeholder="{{ __('Enter street number') }}" />
                    </x-forms.field>

                    <x-forms.field label="{{ __('City') }}" name="registered_city" required>
                        <x-forms.input name="registered_city" wire:model="registered_city"
                            placeholder="{{ __('Enter city') }}" required />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Zip Code') }}" name="registered_zip_code" required>
                        <x-forms.input name="registered_zip_code" wire:model="registered_zip_code"
                            placeholder="{{ __('Enter zip code') }}" required />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Province') }}" name="registered_province">
                        <x-forms.input name="registered_province" wire:model="registered_province"
                            placeholder="{{ __('Enter province') }}" />
                    </x-forms.field>
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-amber-50 rounded-lg">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ __('Premises Address') }}
                        </h3>
                    </div>

                    <x-forms.field name="has_separate_premises">
                        <x-forms.checkbox name="has_separate_premises" wire:model.live="has_separate_premises"
                            label="{{ __('Different from registered address') }}"
                            description="{{ __('Check if pickup location differs from registered address') }}" />
                    </x-forms.field>
                </div>

                @if (!$has_separate_premises)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ __('Pickup location will use the registered address shown above.') }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-600 -mt-2 ml-14">
                        {{ __('Physical location for waste pickup (will be geocoded for route optimization)') }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <x-forms.field class="md:col-span-2" label="{{ __('Street Name') }}"
                            name="premises_street_name" required>
                            <x-forms.input name="premises_street_name" wire:model="premises_street_name"
                                placeholder="{{ __('Enter street name') }}" required />
                        </x-forms.field>

                        <x-forms.field label="{{ __('Street Number') }}" name="premises_street_number">
                            <x-forms.input name="premises_street_number" wire:model="premises_street_number"
                                placeholder="{{ __('Enter street number') }}" />
                        </x-forms.field>

                        <x-forms.field label="{{ __('City') }}" name="premises_city" required>
                            <x-forms.input name="premises_city" wire:model="premises_city"
                                placeholder="{{ __('Enter city') }}" required />
                        </x-forms.field>

                        <x-forms.field label="{{ __('Zip Code') }}" name="premises_zip_code" required>
                            <x-forms.input name="premises_zip_code" wire:model="premises_zip_code"
                                placeholder="{{ __('Enter zip code') }}" required />
                        </x-forms.field>

                        <x-forms.field label="{{ __('Province') }}" name="premises_province">
                            <x-forms.input name="premises_province" wire:model="premises_province"
                                placeholder="{{ __('Enter province') }}" />
                        </x-forms.field>
                    </div>
                @endif
            </div>

            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                    {{ __('Contact Information') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-forms.field label="{{ __('Contact Person') }}" name="contact_person">
                        <x-forms.input name="contact_person" wire:model="contact_person"
                            placeholder="{{ __('Enter contact person name') }}" :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z\'></path>'" />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Email') }}" name="email" required>
                        <x-forms.input type="email" name="email" wire:model="email"
                            placeholder="{{ __('Enter email address') }}" required :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z\'></path>'" />
                    </x-forms.field>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-medium text-gray-700">
                            {{ __('Phone Numbers') }} <span class="text-red-500">*</span>
                        </label>
                        <button type="button" wire:click="addPhoneNumber"
                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-emerald-700 bg-emerald-100 hover:bg-emerald-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            {{ __('Add Phone Number') }}
                        </button>
                    </div>

                    <div class="space-y-3">
                        @foreach ($phoneNumbers as $index => $phone)
                            <div class="flex gap-3 items-start bg-gray-50 p-4 rounded-lg border border-gray-200"
                                wire:key="phone-{{ $index }}">
                                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <x-forms.input type="tel"
                                            name="phoneNumbers.{{ $index }}.phone_number"
                                            wire:model="phoneNumbers.{{ $index }}.phone_number"
                                            placeholder="{{ __('Enter phone number') }}" :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z\'></path>'" />
                                        @error('phoneNumbers.' . $index . '.phone_number')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <x-forms.input type="text" name="phoneNumbers.{{ $index }}.label"
                                            wire:model="phoneNumbers.{{ $index }}.label"
                                            placeholder="{{ __('Label (e.g., Chef, Owner, Manager)') }}"
                                            :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z\'></path>'" />
                                        @error('phoneNumbers.' . $index . '.label')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 pt-1">
                                    <button type="button" wire:click="setPrimary({{ $index }})"
                                        class="p-2 rounded-md transition-colors {{ $phone['is_primary'] ? 'text-yellow-600 bg-yellow-50 hover:bg-yellow-100' : 'text-gray-400 bg-white hover:bg-gray-100' }}"
                                        title="{{ $phone['is_primary'] ? __('Primary Contact') : __('Set as Primary') }}">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                    </button>

                                    @if (count($phoneNumbers) > 1)
                                        <button type="button" wire:click="removePhoneNumber({{ $index }})"
                                            class="p-2 text-red-600 bg-white hover:bg-red-50 rounded-md transition-colors"
                                            title="{{ __('Remove') }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                    {{ __('Pricing & Settings') }}
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-forms.field label="{{ __('Price Rate') }}" name="price_rate"
                        help="{{ __('Price per unit (optional)') }}">
                        <x-forms.input type="number" step="0.01" name="price_rate" wire:model="price_rate"
                            placeholder="{{ __('Enter price rate') }}" />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Tax Rate (%)') }}" name="tax_rate"
                        help="{{ __('Tax percentage (0-100)') }}">
                        <x-forms.input type="number" step="1" name="tax_rate" wire:model="tax_rate"
                            placeholder="{{ __('Enter tax rate') }}" />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Pickup Frequency') }}" name="pickup_frequency" required>
                        <x-forms.select name="pickup_frequency" wire:model="pickup_frequency" :options="\App\Enums\PickupFrequency::options()"
                            required />
                        <p class="mt-1 text-xs text-gray-500">
                            {{ __('Determines if the system auto-schedules the next pickup after completion.') }}
                        </p>
                    </x-forms.field>

                    <div class="col-span-1 space-y-4">
                        <x-forms.field name="auto_invoice">
                            <x-forms.checkbox name="auto_invoice" wire:model="auto_invoice"
                                label="{{ __('Auto Invoice') }}"
                                description="{{ __('Automatically generate invoices') }}" />
                        </x-forms.field>

                        <x-forms.field name="auto_kpo">
                            <x-forms.checkbox name="auto_kpo" wire:model="auto_kpo" label="{{ __('Auto KPO') }}"
                                description="{{ __('Automatically generate KPO documents') }}" />
                        </x-forms.field>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <x-utils.link-button href="{{ route('clients.index') }}" buttonText="{{ __('Cancel') }}" />
                <x-utils.submit-button wire-target="update" buttonText="{{ __('Update Client') }}"
                    bgColor="bg-emerald-700" hoverColor="hover:bg-emerald-900" focusRing="focus:ring-emerald-600" />
            </div>
        </form>
    </div>
</div>
