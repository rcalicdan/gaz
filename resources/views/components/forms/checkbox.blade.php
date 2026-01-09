@props([
    'name' => null,
    'value' => '1',
    'checked' => false,
    'required' => false,
    'disabled' => false,
    'label' => null,
    'description' => null,
    'error' => null,
])

@php
    $fieldId = $name ?? 'checkbox_' . uniqid();
    $fieldError = $error ?? ($name ? $errors->first($name) : null);
    $checkboxClasses = 'h-5 w-5 text-emerald-800 rounded border-2 transition-colors duration-200 ' .
        ($fieldError
            ? 'border-red-300 focus:ring-red-500/20'
            : 'border-gray-300 focus:ring-emerald-600/20 focus:ring-offset-1');
    $labelClasses = 'ml-3 text-sm font-medium cursor-pointer ' . ($fieldError ? 'text-red-900' : 'text-gray-900');
    $descriptionClasses = 'ml-8 text-sm mt-1 ' . ($fieldError ? 'text-red-600' : 'text-gray-600');
@endphp

<div>
    <div class="flex items-start py-2">
        <div class="flex items-center h-6">
            <input type="checkbox"
                @if ($name) name="{{ $name }}" id="{{ $fieldId }}" @endif
                value="{{ $value }}"
                @if ($checked) checked @endif
                @if ($required) required @endif
                @if ($disabled) disabled @endif
                {{ $attributes->merge(['class' => $checkboxClasses]) }} />
        </div>

        @if ($label)
            <div class="flex-1">
                <label for="{{ $fieldId }}" class="{{ $labelClasses }}">
                    {{ __($label) }}
                </label>
                @if ($description)
                    <p class="{{ $descriptionClasses }}">{{ __($description) }}</p>
                @endif
            </div>
        @endif
    </div>

    @if ($fieldError)
        <p class="ml-8 mt-1 text-sm text-red-600 flex items-center gap-1">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                    clip-rule="evenodd"></path>
            </svg>
            {{ __($fieldError) }}
        </p>
    @endif
</div>
