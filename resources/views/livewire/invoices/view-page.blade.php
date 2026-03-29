<div class="min-h-screen bg-gray-50/50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('invoices.index') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors duration-150 mb-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ __('Back to Invoices') }}
                </a>
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ __('Invoice') }} {{ $invoice->invoice_number }}
                </h1>
            </div>

            <a href="{{ route('pickups.view', $invoice->pickup_id) }}"
                class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:shadow-sm transition-all duration-150">
                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                {{ __('View Original Pickup') }}
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('Ministry of Finance (KSeF) Status') }}
                    </h2>

                    <span
                        class="inline-flex px-3 py-1 text-sm font-bold rounded-full 
                        {{ match ($invoice->ksef_status_color) {
                            'green' => 'bg-green-100 text-green-800',
                            'blue' => 'bg-blue-100 text-blue-800',
                            'red' => 'bg-red-100 text-red-800',
                            'purple' => 'bg-purple-100 text-purple-800',
                            default => 'bg-gray-100 text-gray-800',
                        } }}">
                        {{ $invoice->ksef_status_label }}
                    </span>
                </div>

                <div class="p-6 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex-1 flex gap-8">
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">{{ __('KSeF Reference / Number') }}</label>
                            <p
                                class="text-lg font-mono font-medium {{ $invoice->ksef_reference_number ? 'text-gray-900' : 'text-gray-400 italic' }}">
                                {{ $invoice->ksef_reference_number_display }}
                            </p>
                        </div>

                        {{-- NEW: Email Status Indicator --}}
                        <div class="border-l border-gray-200 pl-8">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Email Status') }}</label>
                            @if($invoice->is_emailed)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-sm font-medium bg-green-50 text-green-700 border border-green-200">
                                    <i class="fas fa-check-circle"></i> {{ __('Sent') }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-sm font-medium bg-red-50 text-red-700 border border-red-200">
                                    <i class="fas fa-times-circle"></i> {{ __('Not Sent') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex gap-3">
                        @if ($invoice->ksef_status === \App\Enums\KsefStatus::SENT_TO_KSEF)
                            <button wire:click="syncStatus" wire:loading.attr="disabled"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg font-medium shadow-sm hover:bg-blue-700 transition-colors">
                                <i class="fas fa-sync-alt mr-2" wire:loading.class="fa-spin"
                                    wire:target="syncStatus"></i>
                                {{ __('Check Status') }}
                            </button>
                        @endif

                        @if ($invoice->ksef_status->canResend())
                            <button wire:click="retrySend" wire:loading.attr="disabled"
                                class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg font-medium shadow-sm hover:bg-emerald-700 transition-colors">
                                <i class="fas fa-paper-plane mr-2" wire:loading.class="fa-animate-ping"
                                    wire:target="retrySend"></i>
                                {{ __('Send to KSeF') }}
                            </button>
                        @endif

                        {{-- NEW: Resend Email Button (Only shows when KSeF accepted the invoice) --}}
                        @if($invoice->ksef_status === \App\Enums\KsefStatus::ACCEPTED)
                            <button wire:click="resendEmail" wire:loading.attr="disabled" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium shadow-sm hover:bg-indigo-700 transition-colors">
                                <i class="fas fa-envelope mr-2" wire:loading.class="fa-bounce" wire:target="resendEmail"></i>
                                {{ $invoice->is_emailed ? __('Resend Email') : __('Send Email') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">{{ __('Financial Details') }}</h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase">{{ __('Net Amount') }}</label>
                        <p class="text-xl font-semibold text-gray-900">{{ number_format($invoice->net_amount, 2) }}
                            {{ $invoice->client->currency ?? 'PLN' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase">{{ __('VAT Amount') }}</label>
                        <p class="text-xl font-semibold text-gray-900">{{ number_format($invoice->vat_amount, 2) }}
                            {{ $invoice->client->currency ?? 'PLN' }}</p>
                    </div>
                    <div class="col-span-2 pt-4 border-t border-gray-100">
                        <label
                            class="block text-xs font-bold text-gray-400 uppercase">{{ __('Gross Amount (To Pay)') }}</label>
                        <p class="text-3xl font-bold text-emerald-700">{{ number_format($invoice->gross_amount, 2) }}
                            {{ $invoice->client->currency ?? 'PLN' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">{{ __('Seller Details (Self-Billed)') }}</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase">{{ __('Company') }}</label>
                        <p class="text-sm font-semibold text-gray-900">{{ $invoice->client->company_name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase">{{ __('VAT ID') }}</label>
                        <p class="text-sm font-mono text-gray-700">{{ $invoice->client->vat_id }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase">{{ __('Address') }}</label>
                        <p class="text-sm text-gray-600">{{ $invoice->client->full_address }}</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="fas fa-history text-emerald-600 mr-2"></i>
                        {{ __('KSeF Activity Log') }}
                    </h2>
                    <button wire:click="$refresh"
                        class="text-sm text-gray-500 hover:text-emerald-600 flex items-center transition-colors">
                        <i class="fas fa-sync-alt mr-1"></i> {{ __('Refresh') }}
                    </button>
                </div>

                <div class="p-6">
                    @if ($invoice->logs->isEmpty())
                        <div class="text-center py-6 text-gray-500 text-sm">
                            <i class="fas fa-clipboard-list text-3xl text-gray-300 mb-3 block"></i>
                            {{ __('No activity recorded yet.') }}
                        </div>
                    @else
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach ($invoice->logs as $log)
                                    <li>
                                        <div class="relative pb-8">
                                            @if (!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                                                    aria-hidden="true"></span>
                                            @endif

                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span
                                                        class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                                        {{ match ($log->level) {
                                                            'success' => 'bg-green-500',
                                                            'error' => 'bg-red-500',
                                                            'warning' => 'bg-yellow-500',
                                                            default => 'bg-blue-500',
                                                        } }}">

                                                        @if ($log->level === 'success')
                                                            <i class="fas fa-check text-white text-xs"></i>
                                                        @elseif($log->level === 'error')
                                                            <i class="fas fa-times text-white text-xs"></i>
                                                        @elseif($log->level === 'warning')
                                                            <i class="fas fa-exclamation text-white text-xs"></i>
                                                        @else
                                                            <i class="fas fa-info text-white text-xs"></i>
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-800 font-medium">
                                                            {{ $log->message }}</p>

                                                        @if ($log->context)
                                                            <div
                                                                class="mt-2 text-xs bg-red-50 text-red-700 p-3 rounded-lg border border-red-100 font-mono overflow-x-auto">
                                                                <pre>{{ json_encode($log->context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="whitespace-nowrap text-right text-xs text-gray-500">
                                                        {{ $log->created_at->format('d.m.Y H:i:s') }}
                                                        <div class="mt-1 text-[10px] text-gray-400">
                                                            {{ $log->created_at->diffForHumans() }}
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
</div>