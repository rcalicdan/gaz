<div class="min-h-screen bg-gray-50/50 py-10" x-data="{ showEmailModal: false }"
    @close-custom-email-modal.window="showEmailModal = false">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header Section --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('kpo-documents.index') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-emerald-700 transition-colors duration-150 mb-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ __('Back to KPO Documents') }}
                </a>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                    {{ __('KPO') }} {{ $kpoDocument->kpo_number }}
                    @if ($kpoDocument->is_emailed)
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                            <i class="fas fa-check-circle"></i> {{ __('Sent') }}
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                            <i class="fas fa-clock"></i> {{ __('Pending Email') }}
                        </span>
                    @endif
                </h1>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('pickups.view', $kpoDocument->pickup_id) }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:shadow-sm transition-all duration-150">
                    <i class="fas fa-truck-pickup mr-2 text-gray-500"></i>
                    {{ __('View Pickup') }}
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- PDF Management Card --}}
            <div
                class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-file-pdf text-red-500 mr-2 text-xl"></i>
                        {{ __('Document PDF File') }}
                    </h2>

                    @if ($kpoDocument->hasPdf())
                        <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <div
                                class="h-12 w-12 rounded-lg bg-red-100 text-red-600 flex items-center justify-center text-2xl">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900">KPO_{{ $kpoDocument->kpo_number }}.pdf</h3>
                                <p class="text-sm text-gray-500">
                                    {{ __('Size:') }} {{ $kpoDocument->getPdfSizeForHumans() }} &bull;
                                    {{ __('Generated:') }} {{ $kpoDocument->pdf_generated_at?->format('d.m.Y H:i') }}
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
                            <div class="flex">
                                <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-3"></i>
                                <div>
                                    <h3 class="font-bold text-red-800">{{ __('PDF Missing') }}</h3>
                                    <p class="text-sm text-red-700 mt-1">
                                        {{ __('The PDF file for this KPO has not been generated or is missing from storage.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex gap-3 mt-6 pt-6 border-t border-gray-100">
                    <button wire:click="downloadPdf" wire:loading.attr="disabled"
                        @if (!$kpoDocument->hasPdf()) disabled @endif
                        class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg font-medium shadow-sm hover:bg-gray-900 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-download mr-2"></i> {{ __('Download PDF') }}
                    </button>

                    <button wire:click="regeneratePdf" wire:loading.attr="disabled"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
                        <i class="fas fa-sync-alt mr-2" wire:loading.class="fa-spin" wire:target="regeneratePdf"></i>
                        {{ __('Regenerate PDF') }}
                    </button>
                </div>
            </div>

            {{-- KPO Details Card --}}
            <div class="lg:col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">{{ __('Waste Details') }}</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase">{{ __('Client') }}</label>
                        <p class="text-sm font-semibold text-gray-900">{{ $kpoDocument->client->company_name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase">{{ __('Waste Type') }}</label>
                        <p class="text-sm text-gray-800">
                            <span
                                class="inline-block bg-gray-100 px-2 py-1 rounded font-mono text-xs mr-1">{{ $kpoDocument->waste_code }}</span>
                            {{ $kpoDocument->pickup->wasteType->name ?? '' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase">{{ __('Quantity') }}</label>
                        <p class="text-xl font-bold text-emerald-700">{{ number_format($kpoDocument->quantity, 2) }} kg
                        </p>
                    </div>
                </div>
            </div>

            {{-- Email Timeline Card --}}
            <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-2">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="fas fa-envelope text-indigo-600 mr-2 text-xl"></i>
                        {{ __('Email Communication Log') }}
                    </h2>

                    <div class="flex gap-2">
                        <button @click="showEmailModal = true"
                            class="text-sm bg-white border border-gray-300 text-gray-700 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-paper-plane mr-1 text-gray-400"></i> {{ __('Send Custom Email') }}
                        </button>

                        <button wire:click="sendEmail" wire:loading.attr="disabled"
                            class="text-sm bg-indigo-600 text-white px-3 py-1.5 rounded-lg hover:bg-indigo-700 transition-colors">
                            <i class="fas fa-envelope mr-1" wire:loading.class="fa-bounce" wire:target="sendEmail"></i>
                            {{ __('Send to Client') }}
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    @if ($kpoDocument->emailLogs->isEmpty())
                        <div class="text-center py-10 text-gray-500 text-sm">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-3 block"></i>
                            {{ __('No emails have been sent for this document yet.') }}
                        </div>
                    @else
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach ($kpoDocument->emailLogs as $log)
                                    <li>
                                        <div class="relative pb-8">
                                            @if (!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                                                    aria-hidden="true"></span>
                                            @endif

                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span
                                                        class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white {{ $log->status->color() === 'green' ? 'bg-green-500' : ($log->status->color() === 'red' ? 'bg-red-500' : 'bg-yellow-500') }}">
                                                        <i
                                                            class="fas fa-{{ $log->status->icon() }} text-white text-xs"></i>
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-800 font-medium">
                                                            {{ __('Sent to:') }} <a
                                                                href="mailto:{{ $log->recipient_email }}"
                                                                class="text-indigo-600 hover:underline">{{ $log->recipient_email }}</a>
                                                        </p>
                                                        <p class="text-xs text-gray-500 mt-0.5">
                                                            {{ __('Status:') }} <span
                                                                class="font-bold {{ $log->status->color() === 'green' ? 'text-green-600' : 'text-red-600' }}">{{ $log->status->label() }}</span>
                                                            @if ($log->sentBy)
                                                                &bull; {{ __('By:') }}
                                                                {{ $log->sentBy->full_name }}
                                                            @endif
                                                        </p>

                                                        @if ($log->error_message)
                                                            <div
                                                                class="mt-2 text-xs bg-red-50 text-red-700 p-3 rounded-lg border border-red-100 font-mono">
                                                                {{ $log->error_message }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="whitespace-nowrap text-right text-xs text-gray-500">
                                                        {{ $log->sent_at->format('d.m.Y H:i') }}
                                                        <div class="mt-1 text-[10px] text-gray-400">
                                                            {{ $log->sent_at->diffForHumans() }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- Alpine Modal for Custom Email --}}
    <div x-show="showEmailModal" style="display: none;" class="relative z-50" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div x-show="showEmailModal" x-transition.opacity
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="showEmailModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @click.away="showEmailModal = false"
                    class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">

                    <div class="absolute right-0 top-0 hidden pr-4 pt-4 sm:block">
                        <button type="button" @click="showEmailModal = false"
                            class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <i class="fas fa-times text-lg"></i>
                        </button>
                    </div>

                    <div class="sm:flex sm:items-start mb-5">
                        <div
                            class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-paper-plane text-indigo-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">
                                {{ __('Send KPO to Custom Email') }}</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ __('Enter an alternative email address to send this document to.') }}</p>
                        </div>
                    </div>

                    <form wire:submit.prevent="sendCustomEmail">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email Address') }}
                                    <span class="text-red-500">*</span></label>
                                <input type="email" wire:model="customEmail"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="adres@example.com" required>
                                @error('customEmail')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700 mb-1">{{ __('Custom Message (Optional)') }}</label>
                                <textarea wire:model="customMessage" rows="3"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="{{ __('Add a personal note to the email body...') }}"></textarea>
                                @error('customMessage')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button type="submit" wire:loading.attr="disabled"
                                class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto disabled:opacity-50">
                                <i class="fas fa-spinner fa-spin mr-2" wire:loading wire:target="sendCustomEmail"></i>
                                {{ __('Send Email') }}
                            </button>
                            <button type="button" @click="showEmailModal = false"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                                {{ __('Cancel') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

</div>
