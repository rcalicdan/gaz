<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" wire:key="tab-overview">

    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-900/5 transition-all duration-300 hover:shadow-md hover:-translate-y-1 group">
        <div class="px-6 py-5 border-b border-emerald-100 bg-gradient-to-r from-emerald-50 to-white rounded-t-2xl">
            <h3 class="text-base font-semibold leading-6 text-gray-900 flex items-center gap-3">
                <div class="p-2 bg-white ring-1 ring-emerald-100 rounded-lg shadow-sm text-emerald-600 group-hover:text-emerald-700 group-hover:ring-emerald-300 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                {{ __('Contact Details') }}
            </h3>
        </div>
        <div class="px-6 py-6 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-24 h-24 bg-white rounded-full opacity-0 blur-2xl pointer-events-none"></div>

            <dl class="space-y-6 relative z-10">
                <div>
                    <dt class="text-xs font-bold text-emerald-700 uppercase tracking-wider">{{ __('Contact Person') }}</dt>
                    <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $client->contact_person ?? __('Not provided') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-emerald-700 uppercase tracking-wider">{{ __('Email') }}</dt>
                    <dd class="mt-1">
                        <a href="mailto:{{ $client->email }}" class="text-sm font-medium text-gray-700 hover:text-emerald-600 hover:underline transition-colors">
                            {{ $client->email }}
                        </a>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-emerald-700 uppercase tracking-wider">{{ __('Phone') }}</dt>
                    <dd class="mt-1">
                        <a href="tel:{{ $client->phone_number }}" class="text-sm font-medium text-gray-700 hover:text-emerald-600">
                            {{ $client->phone_number ?? '-' }}
                        </a>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-900/5 transition-all duration-300 hover:shadow-md hover:-translate-y-1 group">
        <div class="px-6 py-5 border-b border-emerald-100 bg-gradient-to-r from-emerald-50 to-white rounded-t-2xl">
            <h3 class="text-base font-semibold leading-6 text-gray-900 flex items-center gap-3">
                <div class="p-2 bg-white ring-1 ring-emerald-100 rounded-lg shadow-sm text-emerald-800 group-hover:text-emerald-900 group-hover:ring-emerald-300 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </div>
                {{ __('Company Details') }}
            </h3>
        </div>
        <div class="px-6 py-6 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-24 h-24 bg-white rounded-full opacity-0 blur-2xl pointer-events-none"></div>

            <dl class="space-y-6 relative z-10">
                <div>
                    <dt class="text-xs font-bold text-emerald-700 uppercase tracking-wider">{{ __('VAT ID') }}</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-sm font-mono font-medium bg-gray-50 text-gray-700 border border-gray-200">
                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            {{ $client->vat_id }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-emerald-700 uppercase tracking-wider">{{ __('Brand Category') }}</dt>
                    <dd class="mt-2">
                        <span class="inline-flex items-center rounded-full bg-white px-2.5 py-1 text-xs font-medium text-emerald-700 ring-1 ring-inset ring-emerald-100">
                            {{ $client->brand_category ?? __('Standard') }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-900/5 transition-all duration-300 hover:shadow-md hover:-translate-y-1 group">
        <div class="px-6 py-5 border-b border-emerald-100 bg-gradient-to-r from-emerald-50 to-white rounded-t-2xl">
            <h3 class="text-base font-semibold leading-6 text-gray-900 flex items-center gap-3">
                <div class="p-2 bg-white ring-1 ring-emerald-100 rounded-lg shadow-sm text-emerald-600 group-hover:text-emerald-700 group-hover:ring-emerald-300 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                {{ __('Service Settings') }}
            </h3>
        </div>
        <div class="px-6 py-6 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-24 h-24 bg-white rounded-full opacity-0 blur-2xl pointer-events-none"></div>

            <dl class="space-y-6 relative z-10">
                <div class="flex justify-between items-center border-b border-emerald-50/50 pb-3">
                    <dt class="text-sm font-medium text-gray-500">{{ __('Default Waste') }}</dt>
                    <dd class="text-sm font-bold text-emerald-900">{{ $client->defaultWasteType->name ?? __('Not set') }}</dd>
                </div>
                <div class="flex justify-between items-center border-b border-emerald-50/50 pb-3">
                    <dt class="text-sm font-medium text-gray-500">{{ __('Price Rate') }}</dt>
                    <dd class="text-sm font-bold text-emerald-900">{{ $client->price_rate }} {{ $client->currency }}</dd>
                </div>

                <div class="flex justify-between items-center border-b border-emerald-50/50 pb-3">
                    <dt class="text-sm font-medium text-gray-500">{{ __('Frequency') }}</dt>
                    <dd class="text-sm font-bold">
                        @if($client->pickup_frequency && $client->pickup_frequency !== \App\Enums\PickupFrequency::ON_DEMAND)
                            <span class="inline-flex items-center gap-1.5 text-emerald-700 bg-white px-2.5 py-0.5 rounded-full text-xs font-semibold ring-1 ring-inset ring-emerald-100">
                                <span class="relative flex h-1.5 w-1.5">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                                </span>
                                {{ $client->pickup_frequency->label() }}
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                {{ $client->pickup_frequency?->label() ?? __('On Demand') }}
                            </span>
                        @endif
                    </dd>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-1">
                    <div class="flex flex-col items-center justify-center p-3 rounded-xl transition-colors {{ $client->auto_invoice ? 'bg-white text-emerald-700 ring-1 ring-emerald-100' : 'bg-gray-50 text-gray-400 ring-1 ring-gray-200' }}">
                        <span class="text-[10px] font-black uppercase tracking-widest mb-1">{{ __('Invoice') }}</span>
                        @if($client->auto_invoice)
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        @endif
                    </div>
                    <div class="flex flex-col items-center justify-center p-3 rounded-xl transition-colors {{ $client->auto_kpo ? 'bg-white text-emerald-700 ring-1 ring-emerald-100' : 'bg-gray-50 text-gray-400 ring-1 ring-gray-200' }}">
                        <span class="text-[10px] font-black uppercase tracking-widest mb-1">{{ __('KPO') }}</span>
                        @if($client->auto_kpo)
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        @endif
                    </div>
                </div>
            </dl>
        </div>
    </div>
</div>
