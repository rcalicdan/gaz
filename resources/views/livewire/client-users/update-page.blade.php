<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 my-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ __('Edit App User') }}</h2>
                <p class="text-sm text-gray-500">{{ $email }}</p>
            </div>
            
            <button type="button" wire:click="$toggle('active')" class="inline-flex items-center px-3 py-1.5 border rounded-md text-sm font-medium transition-colors {{ $active ? 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100' : 'bg-red-50 text-red-700 border-red-200 hover:bg-red-100' }}">
                <i class="fas {{ $active ? 'fa-check' : 'fa-times' }} mr-2"></i>
                {{ $active ? __('Account is Active') : __('Account is Suspended') }}
            </button>
        </div>

        <form wire:submit.prevent="update" class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-forms.field class="md:col-span-2" label="{{ __('Linked Company') }}" name="client_id" required>
                    <livewire:components.searchable-select 
                        :modelClass="\App\Models\Client::class" 
                        :selected="$client_id" 
                        name="client_id" 
                        placeholder="{{ __('Search company by NIP or Name...') }}" 
                        displayField="company_name" 
                        :searchFields="['company_name', 'vat_id']" 
                        :required="true"
                        :key="'client-select-' . $user->id" />
                </x-forms.field>

                <x-forms.field label="{{ __('First Name') }}" name="first_name" required>
                    <x-forms.input name="first_name" wire:model="first_name" required />
                </x-forms.field>

                <x-forms.field label="{{ __('Last Name') }}" name="last_name" required>
                    <x-forms.input name="last_name" wire:model="last_name" required />
                </x-forms.field>

                <x-forms.field class="md:col-span-2" label="{{ __('Email Address') }}" name="email" required>
                    <x-forms.input type="email" name="email" wire:model="email" required />
                </x-forms.field>

                <x-forms.field class="md:col-span-2" label="{{ __('Reset Password') }}" name="password" help="{{ __('Leave blank to keep the current password.') }}">
                    <x-forms.input type="password" name="password" wire:model="password" placeholder="{{ __('Enter new password') }}" />
                </x-forms.field>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <x-utils.link-button href="{{ route('client-users.index') }}" buttonText="{{ __('Cancel') }}" />
                <x-utils.submit-button wire-target="update" buttonText="{{ __('Save Changes') }}" bgColor="bg-emerald-700" hoverColor="hover:bg-emerald-900" />
            </div>
        </form>
    </div>
</div>