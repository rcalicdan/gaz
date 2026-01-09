<div class="min-h-screen bg-gray-50/50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <a href="{{ route('clients.index') }}" class="group flex items-center text-sm font-medium text-gray-500 hover:text-emerald-700 transition-colors">
                    <svg class="flex-shrink-0 w-4 h-4 mr-2 text-gray-400 group-hover:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/></svg>
                {{ __('Back to Clients') }}
                </a>
            </nav>

            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h2 class="text-3xl font-bold leading-7 text-gray-900 sm:truncate sm:text-4xl sm:tracking-tight">
                        {{ $client->company_name }}
                    </h2>
                    <div class="mt-2 flex flex-col sm:flex-row sm:flex-wrap sm:space-x-6">
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5" /></svg>
                            {{ __('ID') }}: #{{ $client->id }}
                        </div>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0h18M5 10.5h.008v.008H5V10.5zm0 4.5h.008v.008H5V15z" /></svg>
                            {{ __('Added') }} {{ $client->created_at->format('d.m.Y') }}
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0">
                    <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-all">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M2.695 14.763l-1.262 3.154a.5.5 0 00.65.65l3.155-1.262a4 4 0 001.343-.885L17.5 5.5a2.25 2.25 0 00-3.182-3.182l-10.879 10.88a4 4 0 00-.885 1.342zM15.75 7.5l-2.25-2.25" /></svg>
                        {{ __('Edit Details') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="border-b border-gray-200 mb-8">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                @foreach ([
                    'overview' => 'Overview',
                    'address' => 'Address & Map',
                    'pickups' => 'Pickup History',
                ] as $key => $label)
                    <button
                        wire:click="setTab('{{ $key }}')"
                        class="{{ $activeTab === $key
                            ? 'border-emerald-700 text-emerald-700'
                            : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }}
                            group inline-flex items-center border-b-2 py-4 px-1 text-sm font-medium transition-all duration-200 ease-in-out">
                        {{ __($label) }}
                        @if($key === 'pickups')
                            <span class="{{ $activeTab === $key ? 'bg-white text-emerald-700 ring-1 ring-emerald-100' : 'bg-gray-100 text-gray-900' }} ml-3 hidden rounded-full py-0.5 px-2.5 text-xs font-medium md:inline-block">
                                {{ $client->pickups->count() }}
                            </span>
                        @endif
                    </button>
                @endforeach
            </nav>
        </div>

        <div class="min-h-[400px]">
            @if ($activeTab === 'overview')
                <div wire:key="overview-tab">
                    @include('livewire.clients.tabs.overview')
                </div>
            @elseif($activeTab === 'address')
                <div wire:key="address-tab-{{ $client->id }}">
                    @include('livewire.clients.tabs.address-map')
                </div>
            @elseif($activeTab === 'pickups')
                <div wire:key="pickups-tab">
                    @include('livewire.clients.tabs.pickups')
                </div>
            @endif
        </div>
    </div>
</div>

@include('livewire.clients.assets.map-script')
