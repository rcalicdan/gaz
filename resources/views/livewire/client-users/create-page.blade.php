<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 my-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-semibold text-gray-900">{{ __('Create App User') }}</h2>
            <p class="text-sm text-gray-500">{{ __('Manually create an account for a client to access the mobile app.') }}</p>
        </div>

        <form wire:submit.prevent="save" class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-forms.field class="md:col-span-2" label="{{ __('Link to Company') }}" name="client_id" required>
                    <livewire:components.searchable-select 
                        :modelClass="\App\Models\Client::class" 
                        :selected="$client_id" 
                        name="client_id" 
                        placeholder="{{ __('Search company by NIP or Name...') }}" 
                        displayField="company_name" 
                        :searchFields="['company_name', 'vat_id']" 
                        :required="true" />
                </x-forms.field>

                <x-forms.field label="{{ __('First Name') }}" name="first_name" required>
                    <x-forms.input name="first_name" wire:model="first_name" placeholder="{{ __('Enter first name') }}" required />
                </x-forms.field>

                <x-forms.field label="{{ __('Last Name') }}" name="last_name" required>
                    <x-forms.input name="last_name" wire:model="last_name" placeholder="{{ __('Enter last name') }}" required />
                </x-forms.field>

                <x-forms.field class="md:col-span-2" label="{{ __('Email Address') }}" name="email" required>
                    <x-forms.input type="email" name="email" wire:model="email" placeholder="{{ __('Enter email address') }}" required />
                </x-forms.field>

                <x-forms.field label="{{ __('Password') }}" name="password" required>
                    <x-forms.input type="password" name="password" wire:model="password" placeholder="{{ __('Enter password') }}" required />
                </x-forms.field>

                <x-forms.field label="{{ __('Confirm Password') }}" name="password_confirmation" required>
                    <x-forms.input type="password" name="password_confirmation" wire:model="password_confirmation" placeholder="{{ __('Confirm password') }}" required />
                </x-forms.field>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <x-utils.link-button href="{{ route('client-users.index') }}" buttonText="{{ __('Cancel') }}" />
                <x-utils.submit-button wire-target="save" buttonText="{{ __('Create User') }}" bgColor="bg-emerald-700" hoverColor="hover:bg-emerald-900" />
            </div>
        </form>
    </div>
</div>