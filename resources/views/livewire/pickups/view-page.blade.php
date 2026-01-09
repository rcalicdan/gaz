<div class="min-h-screen bg-white py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <a href="{{ route('pickups.index') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors duration-150 mb-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        {{ __('Back to Pickups') }}
                    </a>
                    <div class="flex items-baseline gap-4">
                        <h1 class="text-3xl font-bold text-gray-900">
                            {{ __('Pickup') }} #{{ $pickup->id }}
                        </h1>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border"
                            style="background-color: {{ $pickup->status->color() === 'yellow' ? '#fffbeb' : ($pickup->status->color() === 'blue' ? '#eff6ff' : ($pickup->status->color() === 'green' ? '#ecfdf5' : ($pickup->status->color() === 'orange' ? '#fff7ed' : '#fef2f2'))) }};
                                   border-color: {{ $pickup->status->color() === 'yellow' ? '#fcd34d' : ($pickup->status->color() === 'blue' ? '#bfdbfe' : ($pickup->status->color() === 'green' ? '#6ee7b7' : ($pickup->status->color() === 'orange' ? '#fdba74' : '#fca5a5'))) }};
                                   color: {{ $pickup->status->color() === 'yellow' ? '#92400e' : ($pickup->status->color() === 'blue' ? '#1e40af' : ($pickup->status->color() === 'green' ? '#065f46' : ($pickup->status->color() === 'orange' ? '#9a3412' : '#991b1b'))) }};">
                            <span class="w-2 h-2 rounded-full mr-2"
                                style="background-color: currentColor"></span>
                            {{ $pickup->status->label() }}
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('Created') }} {{ $pickup->created_at->format('d.m.Y \o H:i') }}
                    </p>
                </div>

                <a href="{{ route('pickups.edit', $pickup) }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:shadow-sm transition-all duration-150">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ __('Edit Pickup') }}
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8 auto-rows-fr">
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100 flex flex-col h-full">
                <div class="px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2 mr-3">
                            <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-emerald-700">{{ __('Client Information') }}</h2>
                    </div>
                </div>
                <div class="p-6 flex-1 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Company Name') }}</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $pickup->client->company_name }}</p>
                    </div>

                    <div class="grid grid-cols-1 gap-3 pt-3 border-t border-gray-100">
                        @if($pickup->client->contact_person)
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Contact Person') }}</label>
                            <p class="text-sm font-medium text-gray-900">{{ $pickup->client->contact_person }}</p>
                        </div>
                        @endif

                        @if($pickup->client->email)
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Email') }}</label>
                            <a href="mailto:{{ $pickup->client->email }}" class="text-sm text-emerald-600 hover:text-emerald-800 hover:underline">
                                {{ $pickup->client->email }}
                            </a>
                        </div>
                        @endif

                        @if($pickup->client->phone_number)
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Phone') }}</label>
                            <a href="tel:{{ $pickup->client->phone_number }}" class="text-sm text-emerald-600 hover:text-emerald-800 hover:underline">
                                {{ $pickup->client->phone_number }}
                            </a>
                        </div>
                        @endif

                        @if($pickup->client->full_address)
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Address') }}</label>
                            <p class="text-sm text-gray-600 leading-snug">{{ $pickup->client->full_address }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100 flex flex-col h-full">
                <div class="px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2 mr-3">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ __('Pickup Details') }}</h2>
                    </div>
                </div>
                <div class="p-6 flex-1 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Scheduled') }}</label>
                            <p class="text-lg font-semibold text-gray-900">{{ $pickup->scheduled_date->format('d.m.Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Completed At') }}</label>
                            <p class="text-base font-medium text-gray-900">
                                {{ $pickup->actual_pickup_time ? $pickup->actual_pickup_time->format('H:i') : '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-100">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Waste Type') }}</label>
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-white text-emerald-800 border border-emerald-200">
                                {{ $pickup->wasteType->code }}
                            </span>
                            <span class="ml-2 text-sm text-gray-700">{{ $pickup->wasteType->name }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-3 border-t border-gray-100">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Quantity') }}</label>
                            @if($pickup->waste_quantity)
                                <p class="text-base font-semibold text-gray-900">{{ number_format($pickup->waste_quantity, 2) }} <span class="text-xs text-gray-500 font-normal">kg</span></p>
                            @else
                                <span class="text-sm text-gray-400 italic">{{ __('Not set') }}</span>
                            @endif
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Certificate') }}</label>
                            <p class="text-sm font-mono text-gray-700">{{ $pickup->certificate_number ?: '-' }}</p>
                        </div>
                    </div>

                    @if($pickup->driver_note)
                    <div class="pt-3 border-t border-gray-100">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Driver Notes') }}</label>
                        <div class="bg-amber-50 border-l-4 border-amber-400 p-3 rounded-r">
                            <p class="text-sm text-gray-700">{{ $pickup->driver_note }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            @if($pickup->driver)
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100 flex flex-col h-full">
                <div class="bg-gradient-to-br from-cyan-500 to-emerald-600 px-6 py-4">
                    <div class="flex items-center">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2 mr-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">{{ __('Driver') }}</h2>
                    </div>
                </div>
                <div class="p-6 flex-1 space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-cyan-100 to-emerald-200 flex items-center justify-center text-cyan-700 font-bold text-lg">
                            {{ substr($pickup->driver->user->full_name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-base font-bold text-gray-900">{{ $pickup->driver->user->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ __('Driver ID') }}: #{{ $pickup->driver->id }}</p>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-gray-100 space-y-3">
                        @if($pickup->driver->license_number)
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('License') }}</label>
                            <p class="text-sm font-mono text-gray-700">{{ $pickup->driver->license_number }}</p>
                        </div>
                        @endif

                        @if($pickup->driver->user->email)
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Email') }}</label>
                            <a href="mailto:{{ $pickup->driver->user->email }}" class="text-sm text-emerald-600 hover:text-emerald-800 hover:underline">
                                {{ $pickup->driver->user->email }}
                            </a>
                        </div>
                        @endif

                        @if($pickup->driver->notes)
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Notes') }}</label>
                            <p class="text-xs text-gray-500 italic">{{ $pickup->driver->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            @if($pickup->applied_price_rate)
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100 flex flex-col h-full">
                <div class="bg-gradient-to-br from-green-500 to-emerald-600 px-6 py-4">
                    <div class="flex items-center">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2 mr-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">{{ __('Pricing') }}</h2>
                    </div>
                </div>
                <div class="p-6 flex-1 flex flex-col justify-center">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Applied Price Rate') }}</label>
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl font-bold text-gray-900">{{ number_format($pickup->applied_price_rate, 2) }}</span>
                            <span class="text-sm font-medium text-gray-500">{{ $pickup->client->currency ?? 'EUR' }}</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">{{ __('Per Unit') }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($pickup->route)
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100 flex flex-col h-full">
                <div class="px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2 mr-3">
                            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ __('Route') }}</h2>
                    </div>
                </div>
                <div class="p-6 flex-1 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Route Name') }}</label>
                        <a href="#" class="text-base font-medium text-emerald-700 hover:text-emerald-900 hover:underline">
                            {{ $pickup->route->name }}
                        </a>
                    </div>

                    @if($pickup->sequence_order)
                    <div class="pt-3 border-t border-gray-100">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('Sequence') }}</label>
                        <div class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-gray-100 text-xs font-bold text-gray-600 border border-gray-200">
                            {{ $pickup->sequence_order }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            @if($pickup->invoice || $pickup->kpoDocument)
            <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100 flex flex-col h-full">
                <div class="bg-gradient-to-br from-amber-500 to-orange-600 px-6 py-4">
                    <div class="flex items-center">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2 mr-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">{{ __('Documents') }}</h2>
                    </div>
                </div>
                <div class="p-6 flex-1 space-y-3">
                    @if($pickup->invoice)
                    <a href="#" class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:border-emerald-300 hover:bg-gray-50 transition-colors duration-150 group">
                        <div class="flex items-center gap-3">
                            <div class="text-emerald-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 group-hover:text-emerald-700">{{ __('Invoice') }}</p>
                                <p class="text-xs text-gray-500">{{ __('View Document') }}</p>
                            </div>
                        </div>
                    </a>
                    @endif

                    @if($pickup->kpoDocument)
                    <a href="#" class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:border-emerald-300 hover:bg-gray-50 transition-colors duration-150 group">
                        <div class="flex items-center gap-3">
                            <div class="text-emerald-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 group-hover:text-green-700">{{ __('KPO Document') }}</p>
                                <p class="text-xs text-gray-500">{{ __('View Document') }}</p>
                            </div>
                        </div>
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100 w-full">
            <div class="bg-gradient-to-r from-orange-500 to-red-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2 mr-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-white">{{ __('Pickup Boxes') }}</h2>
                    </div>
                </div>
            </div>
            <div class="p-5">
                <livewire:pickups.boxes-table :pickup="$pickup" />
            </div>
        </div>

    </div>
</div>
