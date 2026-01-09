<x-layouts.app title="EkoLife System - Panel główny">
    <x-partials.dashboard.content-header title="Panel główny" />

    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 py-8 space-y-8">

        <div class="space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">
                {{ __("Client Reminders") }}
            </h2>

            <div class="h-96 -mx-6 sm:-mx-8 lg:-mx-10">
                <livewire:dashboard.reminders-widget />
            </div>
        </div>

    </div>
</x-layouts.app>
