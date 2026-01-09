<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 my-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <form wire:submit.prevent="update" class="p-6 space-y-8">
            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293z" clip-rule="evenodd"/>
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

            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                    {{ __('Price List Information') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-forms.field label="{{ __('Price List Name') }}" name="name" required>
                        <x-forms.input name="name" wire:model="name"
                            placeholder="{{ __('Enter price list name') }}" required />
                    </x-forms.field>

                    <x-forms.field name="is_active">
                        <x-forms.checkbox name="is_active" wire:model="is_active"
                            :checked="$is_active"
                            label="{{ __('Active') }}"
                            description="{{ __('Set this price list as active') }}" />
                    </x-forms.field>
                </div>

                <x-forms.field label="{{ __('Description') }}" name="description">
                    <x-forms.textarea name="description" wire:model="description"
                        placeholder="{{ __('Enter description (optional)') }}"
                        rows="3" />
                </x-forms.field>
            </div>

            <div class="space-y-6">
                <div class="flex items-center justify-between border-b border-gray-200 pb-2">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ __('Price Items') }}
                    </h3>
                    <button type="button" wire:click="addItem"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-700 text-white text-sm font-medium rounded-lg hover:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-600 transition-colors duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ __('Add Item') }}
                    </button>
                </div>

                @if(count($items) === 0)
                    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">{{ __('No items added yet') }}</p>
                        <button type="button" wire:click="addItem"
                            class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-white text-emerald-900 text-sm font-medium rounded-lg border border-emerald-900 hover:bg-gray-50 transition-colors duration-150">
                            {{ __('Add First Item') }}
                        </button>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($items as $index => $item)
                            <div wire:key="item-{{ $index }}" class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-sm font-semibold text-gray-700">
                                        {{ __('Item') }} #{{ $index + 1 }}
                                    </h4>
                                    @if(count($items) > 1)
                                        <button type="button" wire:click="removeItem({{ $index }})"
                                            class="text-red-600 hover:text-red-800 transition-colors duration-150">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <x-forms.field label="{{ __('Waste Type') }}" name="items.{{ $index }}.waste_type_id" required>
                                        <x-forms.select name="items.{{ $index }}.waste_type_id"
                                            wire:model="items.{{ $index }}.waste_type_id"
                                            placeholder="{{ __('Select waste type') }}"
                                            :options="$wasteTypes->pluck('name', 'id')->toArray()"
                                            required />
                                    </x-forms.field>

                                    <x-forms.field label="{{ __('Base Price') }}" name="items.{{ $index }}.base_price" required>
                                        <x-forms.input type="number" step="0.01"
                                            name="items.{{ $index }}.base_price"
                                            wire:model="items.{{ $index }}.base_price"
                                            placeholder="{{ __('0.00') }}"
                                            required />
                                    </x-forms.field>

                                    <x-forms.field label="{{ __('Currency') }}" name="items.{{ $index }}.currency" required>
                                        <x-forms.select name="items.{{ $index }}.currency"
                                            wire:model="items.{{ $index }}.currency"
                                            :options="['PLN' => 'PLN', 'EUR' => 'EUR', 'USD' => 'USD']"
                                            required />
                                    </x-forms.field>

                                    <x-forms.field label="{{ __('Tax Rate') }}" name="items.{{ $index }}.tax_rate" required
                                        help="{{ __('0.23 = 23%') }}">
                                        <x-forms.input type="number" step="0.01"
                                            name="items.{{ $index }}.tax_rate"
                                            wire:model="items.{{ $index }}.tax_rate"
                                            placeholder="{{ __('0.23') }}"
                                            required />
                                    </x-forms.field>

                                    <x-forms.field label="{{ __('Unit Type') }}" name="items.{{ $index }}.unit_type" required>
                                        <x-forms.select name="items.{{ $index }}.unit_type"
                                            wire:model="items.{{ $index }}.unit_type"
                                            :options="[
                                                'per_pickup' => __('Per Pickup'),
                                                'per_kg' => __('Per Kg'),
                                                'per_ton' => __('Per Ton'),
                                                'per_box' => __('Per Box')
                                            ]"
                                            required />
                                    </x-forms.field>

                                    <x-forms.field label="{{ __('Min Quantity') }}" name="items.{{ $index }}.min_quantity">
                                        <x-forms.input type="number" step="0.01"
                                            name="items.{{ $index }}.min_quantity"
                                            wire:model="items.{{ $index }}.min_quantity"
                                            placeholder="{{ __('Optional') }}" />
                                    </x-forms.field>

                                    <x-forms.field label="{{ __('Max Quantity') }}" name="items.{{ $index }}.max_quantity">
                                        <x-forms.input type="number" step="0.01"
                                            name="items.{{ $index }}.max_quantity"
                                            wire:model="items.{{ $index }}.max_quantity"
                                            placeholder="{{ __('Optional') }}" />
                                    </x-forms.field>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <x-utils.link-button href="{{ route('price-lists.index') }}"
                    buttonText="{{ __('Cancel') }}" />
                <x-utils.submit-button wire-target="update"
                    buttonText="{{ __('Update Price List') }}"
                    bgColor="bg-emerald-700"
                    hoverColor="hover:bg-emerald-900"
                    focusRing="focus:ring-indigo-500" />
            </div>
        </form>
    </div>
</div>
