<div class="grid grid-cols-1 lg:grid-cols-3 gap-8" wire:key="tab-address">
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-900/5 overflow-hidden group">
            <div class="px-6 py-5 border-b border-amber-100 bg-gradient-to-r from-amber-50 to-white">
                <h3 class="text-base font-semibold leading-6 text-gray-900 flex items-center gap-2">
                    <div class="p-1.5 bg-white ring-1 ring-amber-100 rounded-md shadow-sm text-amber-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    {{ __('Location Details') }}
                </h3>
            </div>

            <div class="p-6 relative">
                <div class="relative pl-4 border-l-2 border-amber-400 space-y-1">
                    <p class="text-lg font-bold text-gray-900">
                        {{ $client->street_name }} {{ $client->street_number }}
                    </p>
                    <p class="text-base text-gray-600">
                        {{ $client->zip_code }} {{ $client->city }}
                    </p>
                    <p class="text-sm font-semibold text-amber-600 uppercase tracking-wide">
                        {{ $client->province }}, {{ __('Poland') }}
                    </p>
                </div>

                <hr class="my-6 border-dashed border-gray-200">

                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Geocoding Status') }}</span>

                    @if($client->hasCoordinates())
                        <span class="inline-flex items-center rounded-full bg-teal-50 px-2 py-1 text-xs font-medium text-teal-700 ring-1 ring-inset ring-teal-600/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-teal-600 mr-1.5 animate-pulse"></span>
                            {{ __('Live Coordinates') }}
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-600 mr-1.5"></span>
                            {{ __('Missing Data') }}
                        </span>
                    @endif
                </div>

                @if($client->hasCoordinates())
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <div class="bg-amber-50/50 px-3 py-2 rounded-lg ring-1 ring-amber-900/5">
                            <span class="block text-[10px] uppercase font-bold text-amber-400">{{ __('Latitude') }}</span>
                            <span class="font-mono text-sm text-gray-900">{{ number_format($client->latitude, 6) }}</span>
                        </div>
                        <div class="bg-amber-50/50 px-3 py-2 rounded-lg ring-1 ring-amber-900/5">
                            <span class="block text-[10px] uppercase font-bold text-amber-400">{{ __('Longitude') }}</span>
                            <span class="font-mono text-sm text-gray-900">{{ number_format($client->longitude, 6) }}</span>
                        </div>
                    </div>
                @endif

                <button
                    wire:click="manualGeocode"
                    wire:loading.attr="disabled"
                    class="w-full flex justify-center items-center gap-2 rounded-lg bg-gray-900 px-3.5 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-gray-800 hover:-translate-y-0.5 transition-all focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600 disabled:opacity-50 disabled:cursor-not-allowed">

                    <svg wire:loading.remove wire:target="manualGeocode" class="h-4 w-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                    <svg wire:loading wire:target="manualGeocode" class="animate-spin h-4 w-4 text-amber-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>

                    <span>{{ $client->hasCoordinates() ? __('Recalculate Coordinates') : __('Fetch Coordinates') }}</span>
                </button>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-900/5 overflow-hidden h-[600px] relative isolate"
             x-data="clientMap({
                lat: @js($client->latitude),
                lng: @js($client->longitude),
                hasCoords: @js($client->hasCoordinates())
             })"
             wire:ignore>

            <div id="map-container" class="w-full h-full z-0"></div>

            @if(!$client->hasCoordinates())
                <div class="absolute inset-0 bg-gray-50/90 backdrop-blur-sm flex items-center justify-center z-10">
                    <div class="text-center max-w-sm mx-auto px-6">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-amber-50 mb-4 animate-bounce">
                            <svg class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">{{ __('No Coordinates Available') }}</h3>
                        <p class="mt-2 text-sm text-gray-500">{{ __('The map is hidden because this client lacks geolocation data.') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
