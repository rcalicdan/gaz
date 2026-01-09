<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden h-full flex flex-col">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
        <h3 class="font-semibold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            {{ __('Reminders & Follow-ups') }}
        </h3>

        <div class="flex space-x-1 bg-white p-1 rounded-lg border border-gray-200">
            <button wire:click="setTab('scheduled')"
                class="px-3 py-1 text-xs font-medium rounded-md transition-colors {{ $activeTab === 'scheduled' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700' }}">
                {{ __('Scheduled') }}
            </button>
            <button wire:click="setTab('stale')"
                class="px-3 py-1 text-xs font-medium rounded-md transition-colors {{ $activeTab === 'stale' ? 'bg-orange-100 text-orange-700' : 'text-gray-500 hover:text-gray-700' }}">
                {{ __('Needs Contact') }}
            </button>
        </div>
    </div>

    <div class="flex-1 overflow-auto p-0">
        @if($rows->isEmpty())
            <div class="text-center py-8">
                <div class="h-12 w-12 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="text-sm text-gray-500">{{ __('You are all caught up!') }}</p>
            </div>
        @else
            <ul class="divide-y divide-gray-100">
                @foreach($rows as $row)
                    <li class="p-4 hover:bg-gray-50 transition-colors group">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                @if($activeTab === 'scheduled')
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-bold text-emerald-700 bg-white px-2 py-0.5 rounded border border-emerald-100">
                                            {{ $row->reminder_date->format('M d') }}
                                        </span>
                                        <a href="{{ route('clients.view', $row->client_id) }}" class="text-sm font-medium text-gray-900 hover:text-emerald-700 truncate">
                                            {{ $row->client->company_name }}
                                        </a>
                                    </div>
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $row->note }}</p>
                                @else
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded border border-orange-100">
                                            {{ $row->last_contact_date ? $row->last_contact_date->diffForHumans() : __('Never contacted') }}
                                        </span>
                                    </div>
                                        <a href="{{ route('clients.view', $row->id) }}" class="text-sm font-medium text-gray-900 hover:text-emerald-700 block mb-1">
                                        {{ $row->company_name }}
                                    </a>
                                    <p class="text-xs text-gray-500">{{ $row->city ?? __('No City') }}</p>
                                @endif
                            </div>

                            <div class="ml-4 flex-shrink-0 flex items-center gap-2">
                                @if($activeTab === 'scheduled')
                                    <button wire:click="completeReminder({{ $row->id }})" class="p-1.5 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-full transition-colors" title="{{ __('Mark as Done') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                @else
                                    <button wire:click="openReminderModal({{ $row->id }})" class="p-1.5 text-gray-400 hover:text-emerald-700 hover:bg-gray-50 rounded-full transition-colors" title="{{ __('Add Reminder') }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="px-4 py-2 border-t border-gray-100 bg-gray-50">
                {{ $rows->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </div>

    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('showModal', false)"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div>
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100">
                        <svg class="h-6 w-6 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">{{ __('Set Follow-up Reminder') }}</h3>
                        <p class="text-sm text-gray-500 mt-2">{{ __('When should we remind you to contact this client?') }}</p>
                    </div>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Reminder Date') }}</label>
                            <input type="date" wire:model="modalDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('modalDate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Note') }}</label>
                            <textarea wire:model="modalNote" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="{{ __('e.g. Discuss new pricing...') }}"></textarea>
                            @error('modalNote') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                    <button type="button" wire:click="saveReminder" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-700 text-base font-medium text-white hover:bg-emerald-900 focus:outline-none sm:col-start-2 sm:text-sm">
                        {{ __('Set Reminder') }}
                    </button>
                    <button type="button" wire:click="$set('showModal', false)" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:col-start-1 sm:text-sm">
                        {{ __('Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
