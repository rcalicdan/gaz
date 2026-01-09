<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 my-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <form wire:submit.prevent="update" class="p-6 space-y-6">
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">
                    {{ __('Waste Type Information') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-forms.field label="{{ __('Code') }}" name="code" required>
                        <x-forms.input name="code" wire:model="code"
                            placeholder="{{ __('Enter waste type code') }}" required />
                    </x-forms.field>

                    <x-forms.field label="{{ __('Name') }}" name="name" required>
                        <x-forms.input name="name" wire:model="name"
                            placeholder="{{ __('Enter waste type name') }}" required />
                    </x-forms.field>

                    <x-forms.field class="md:col-span-2" label="{{ __('Description') }}" name="description"
                        help="{{ __('Optional - Provide a description for this waste type') }}">
                        <x-forms.textarea name="description" wire:model="description"
                            placeholder="{{ __('Enter description') }}" rows="4" />
                    </x-forms.field>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <x-utils.link-button href="{{ route('waste-types.index') }}"
                    buttonText="{{ __('Cancel') }}" />
                <x-utils.submit-button wire-target="update"
                    buttonText="{{ __('Update Waste Type') }}"
                    bgColor="bg-emerald-700"
                    hoverColor="hover:bg-emerald-900"
                    focusRing="focus:ring-indigo-500" />
            </div>
        </form>
    </div>
</div>
