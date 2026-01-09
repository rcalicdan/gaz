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
                            placeholder="{{ __('Enter company name') }}" required />
                    </x-forms.field>

                    <x-forms.field label="{{ __('VAT ID') }}" name="vat_id">
                        <x-forms.input name="vat_id" wire:model="vat_id" placeholder="{{ __('Enter VAT ID') }}" />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Brand Category') }}" name="brand_category">
                        <x-forms.input name="brand_category" wire:model="brand_category"
                            placeholder="{{ __('Enter brand category') }}" />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Default Waste Type') }}" name="default_waste_type_id">
                        <x-forms.select name="default_waste_type_id" wire:model="default_waste_type_id"
                            placeholder="{{ __('Select waste type') }}" :options="$wasteTypes->pluck('name', 'id')->toArray()" />
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
                        <x-forms.input name="city" wire:model="city" placeholder="{{ __('Enter city') }}"
                            required />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Zip Code') }}" name="zip_code" required>
                        <x-forms.input name="zip_code" wire:model="zip_code" placeholder="{{ __('Enter zip code') }}"
                            required />
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
                            placeholder="{{ __('Enter email address') }}" required />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Phone Number') }}" name="phone_number" required>
                        <x-forms.input type="tel" name="phone_number" wire:model="phone_number"
                            placeholder="{{ __('Enter phone number') }}" required />
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
                            placeholder="{{ __('Enter price rate') }}" />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Tax Rate (%)') }}" name="tax_rate"
                        help="{{ __('Tax percentage (0-100)') }}">
                        <x-forms.input type="number" step="1" name="tax_rate" wire:model="tax_rate"
                            placeholder="{{ __('Enter tax rate') }}" />
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
