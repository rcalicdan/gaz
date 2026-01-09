<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 my-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900">{{ __('Edit Box') }}</h2>
        </div>

        <form wire:submit.prevent="save" class="p-6 space-y-6">
            <div class="grid grid-cols-1 gap-6">
                <x-forms.field label="{{ __('Box Number') }}" name="box_number" required>
                    <x-forms.input type="text" name="box_number" wire:model="box_number"
                        placeholder="{{ __('Enter box number') }}" required />
                </x-forms.field>

                <x-forms.field label="{{ __('Note') }}" name="note">
                    <x-forms.textarea name="note" wire:model="note"
                        placeholder="{{ __('Optional note') }}" rows="3" />
                </x-forms.field>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                <x-utils.link-button href="{{ route('pickups.view', $pickupBox->pickup_id) }}"
                    buttonText="{{ __('Cancel') }}" />
                <x-utils.submit-button wire-target="save"
                    buttonText="{{ __('Update Box') }}"
                    bgColor="bg-emerald-700"
                    hoverColor="hover:bg-emerald-900"
                    focusRing="focus:ring-emerald-600" />
            </div>
        </form>
    </div>
</div>
