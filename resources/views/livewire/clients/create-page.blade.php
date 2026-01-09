<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 my-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <form wire:submit.prevent="save" class="p-6 space-y-8">
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                    {{ __('Company Information') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-forms.field label="{{ __('Company Name') }}" name="company_name" required>
                        <x-forms.input name="company_name" wire:model="company_name"
                            placeholder="{{ __('Enter company name') }}" required
                            :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4\'></path>'" />
                    </x-forms.field>

                    <x-forms.field label="{{ __('VAT ID') }}" name="vat_id">
                        <x-forms.input name="vat_id" wire:model="vat_id"
                            placeholder="{{ __('Enter VAT ID') }}"
                            :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z\'></path>'" />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Brand Category') }}" name="brand_category">
                        <x-forms.input name="brand_category" wire:model="brand_category"
                            placeholder="{{ __('Enter brand category') }}"
                            :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z\'></path>'" />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Default Waste Type') }}" name="default_waste_type_id">
                        <x-forms.select name="default_waste_type_id" wire:model="default_waste_type_id"
                            placeholder="{{ __('Select waste type') }}"
                            :options="$wasteTypes->pluck('name', 'id')->toArray()" />
                    </x-forms.field>
                </div>
            </div>

            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                    {{ __('Address Information') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <x-forms.field class="md:col-span-2" label="{{ __('Street Name') }}" name="street_name" required>
                        <x-forms.input name="street_name" wire:model="street_name"
                            placeholder="{{ __('Enter street name') }}" required />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Street Number') }}" name="street_number">
                        <x-forms.input name="street_number" wire:model="street_number"
                            placeholder="{{ __('Enter street number') }}" />
                    </x-forms.field>

                    <x-forms.field label="{{ __('City') }}" name="city" required>
                        <x-forms.input name="city" wire:model="city"
                            placeholder="{{ __('Enter city') }}" required />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Zip Code') }}" name="zip_code" required>
                        <x-forms.input name="zip_code" wire:model="zip_code"
                            placeholder="{{ __('Enter zip code') }}" required />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Province') }}" name="province">
                        <x-forms.input name="province" wire:model="province"
                            placeholder="{{ __('Enter province') }}" />
                    </x-forms.field>
                </div>
            </div>

            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                    {{ __('Contact Information') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-forms.field label="{{ __('Email') }}" name="email" required>
                        <x-forms.input type="email" name="email" wire:model="email"
                            placeholder="{{ __('Enter email address') }}" required
                            :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z\'></path>'" />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Phone Number') }}" name="phone_number" required>
                        <x-forms.input type="tel" name="phone_number" wire:model="phone_number"
                            placeholder="{{ __('Enter phone number') }}" required
                            :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z\'></path>'" />
                    </x-forms.field>
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
                            placeholder="{{ __('Enter price rate') }}"
                            :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z\'></path>'" />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Tax Rate (%)') }}" name="tax_rate"
                        help="{{ __('Tax percentage (0-100)') }}">
                        <x-forms.input type="number" step="1" name="tax_rate" wire:model="tax_rate"
                            placeholder="{{ __('Enter tax rate') }}"
                            :icon="'<path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z\'></path>'" />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Pickup Frequency') }}" name="pickup_frequency" required>
                        <x-forms.select name="pickup_frequency" wire:model="pickup_frequency"
                            :options="\App\Enums\PickupFrequency::options()"
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
                            <x-forms.checkbox name="auto_kpo" wire:model="auto_kpo"
                                label="{{ __('Auto KPO') }}"
                                description="{{ __('Automatically generate KPO documents') }}" />
                        </x-forms.field>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <x-utils.link-button href="{{ route('clients.index') }}"
                    buttonText="{{ __('Cancel') }}" />
                <x-utils.submit-button wire-target="save"
                    buttonText="{{ __('Create Client') }}"
                    bgColor="bg-emerald-700"
                    hoverColor="hover:bg-emerald-900"
                    focusRing="focus:ring-indigo-500" />
            </div>
        </form>
    </div>
</div>
