@props([
    'title' => config('app.name'),
    'subtitle' => 'System gospodarowania odpadami',
    'showForgotPassword' => true,
    'showSignUp' => true,
    'submitText' => 'Zaloguj się',
    'signUpText' => 'Zarejestruj się',
    'forgotPasswordUrl' => '#',
    'signUpUrl' => '#',
    'formClass' => 'space-y-5',
    'titleClass' => 'text-3xl sm:text-4xl font-bold text-primary-dark',
    'subtitleClass' => 'mt-2 text-gray-500',
])

<div class="text-center lg:text-left mb-10">
    <h1 class="{{ $titleClass }}">{{ __($title) }}</h1>
    <p class="{{ $subtitleClass }}">{{ __($subtitle) }}</p>
</div>

<form class="{{ $formClass }}" x-data="{ showPassword: false }"
    {{ $attributes->except([
        'title',
        'subtitle',
        'showForgotPassword',
        'showSignUp',
        'submitText',
        'signUpText',
        'forgotPasswordUrl',
        'signUpUrl',
        'formClass',
        'titleClass',
        'subtitleClass',
    ]) }}>

    {{ $slot }}

    <div class="flex items-center justify-between" x-data="{ hasRememberMe: false, hasForgotPassword: {{ $showForgotPassword ? 'true' : 'false' }} }" x-init="hasRememberMe = $el.querySelector('[data-remember-me]') !== null">

        <div x-show="hasRememberMe"></div>

        @if ($showForgotPassword)
            <div class="text-sm" :class="{ 'ml-auto': !hasRememberMe }">
                <a href="{{ $forgotPasswordUrl }}" class="font-semibold text-primary hover:text-primary-dark">
                    {{ __('Forgot password?') }}
                </a>
            </div>
        @endif
    </div>

    <x-forms.auth.button type="submit" variant="primary" full-width>
        {{ __($submitText) }}
    </x-forms.auth.button>

    {{ $formContent ?? '' }}
</form>

@if ($showSignUp)
    <p class="mt-8 text-center text-sm text-gray-500">
        {{ __('Not a member?') }}
        <a href="{{ $signUpUrl }}" class="font-semibold leading-6 text-primary hover:text-primary-dark">
            {{ __($signUpText) }}
        </a>
    </p>
@endif
