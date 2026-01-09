
<section>
    <x-flash-session />
    <div class="text-center mb-10">
        <svg class="mx-auto mb-4 text-primary" style="height:3em;width:3em;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2c-2.21 0-4 1.79-4 4 0 3.31 4 6 4 6s4-2.69 4-6c0-2.21-1.79-4-4-4zM6 14c-1.1 1.1-2 3-2 4 0 .55.45 1 1 1h12c.55 0 1-.45 1-1 0-1-1-2.9-2-4-2 1-5 1-10 0z" fill="currentColor"/>
        </svg>
        <h1 class="text-3xl sm:text-4xl font-bold text-primary-dark">{{ config('app.name') }}</h1>
        <p class="mt-2 text-gray-500">{{ __('Enter your admin credentials') }}</p>
    </div>
    <x-forms.auth.login wire:submit.prevent="login" :show-sign-up="false" :showForgotPassword="false" :title="''" :subtitle="''">
        <x-inputs.auth.email wire:model="email" />
        <x-inputs.auth.password wire:model="password" />
    </x-forms.auth.login>
</section>
