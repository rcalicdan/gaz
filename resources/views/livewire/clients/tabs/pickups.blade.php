<div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-900/5 overflow-hidden" wire:key="tab-pickups">
    <div class="border-b border-gray-200 bg-gray-50/50 px-6 py-5 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-white ring-1 ring-gray-200 rounded-lg text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            </div>
            <div>
                <h3 class="text-base font-semibold leading-6 text-gray-900">{{ __('Pickup History') }}</h3>
                <p class="text-xs text-gray-500">{{ __('Past collection records') }}</p>
            </div>
        </div>
        <div class="flex-shrink-0">
            <span class="inline-flex items-center rounded-lg bg-white px-2.5 py-1 text-xs font-bold text-gray-700 ring-1 ring-inset ring-gray-200 shadow-sm">
                {{ $client->pickups->count() }} {{ __('Records') }}
            </span>
        </div>
    </div>

    @if($client->pickups->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-white text-gray-500 border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 font-medium uppercase text-[11px] tracking-wider">{{ __('ID') }}</th>
                        <th scope="col" class="px-6 py-3 font-medium uppercase text-[11px] tracking-wider">{{ __('Date') }}</th>
                        <th scope="col" class="px-6 py-3 font-medium uppercase text-[11px] tracking-wider">{{ __('Status') }}</th>
                        <th scope="col" class="px-6 py-3 font-medium uppercase text-[11px] tracking-wider">{{ __('Quantity') }}</th>
                        <th scope="col" class="px-6 py-3 font-medium uppercase text-[11px] tracking-wider text-right">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 bg-white">
                    @foreach($client->pickups->sortByDesc('scheduled_date')->take(10) as $pickup)
                        <tr class="hover:bg-gray-50/80 transition-colors group">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                <span class="font-mono text-xs text-gray-400">#</span>{{ $pickup->id }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $pickup->scheduled_date->format('d.m.Y') }}
                            </td>
                            <td class="px-6 py-4">
                                {{-- Vibrant Badges --}}
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset shadow-sm
                                    {{ match($pickup->status->color()) {
                                        'green' => 'bg-white text-emerald-700 ring-emerald-100',
                                        'blue' => 'bg-sky-50 text-sky-700 ring-sky-600/20',
                                        'yellow' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                        'red' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
                                        default => 'bg-gray-50 text-gray-600 ring-gray-500/10'
                                    } }}">
                                    {{ $pickup->status->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-mono text-xs">
                                {{ $pickup->waste_quantity ? number_format($pickup->waste_quantity, 2) . ' kg' : '-' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <x-utils.view-button :route="route('pickups.view', $pickup->id)" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-16 bg-white">
            <div class="mx-auto h-12 w-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-300 mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-900">{{ __('No records found') }}</h3>
            <p class="mt-1 text-sm text-gray-500">{{ __('This client has no pickup history yet.') }}</p>
        </div>
    @endif
</div>
